;(function(window, document) {
  /**
   * Namespace global para TinyMCE custom logic
   */
  window.TinyMCELogic = {
    /**
     * images_upload_handler para TinyMCE 6:
     * - Aquí recibimos blobInfo (imagen) y devolvemos una Promesa.
     * - En la Promesa hacemos un XHR (o Fetch) que:
     *    • Resuelve con la URL final (json.location) si todo va bien.
     *    • Rechaza con un mensaje en caso de error.
     *
     * TinyMCE 6 espera que esta función DEVUELVA una Promesa, no que invoque `success(...)`.
     */
    images_upload_handler: function(blobInfo) {
      return new Promise(function(resolve, reject) {
        // 1) Construir FormData con el archivo y el token CSRF
        var fd = new FormData();
        fd.append('file', blobInfo.blob(), blobInfo.filename());

        var csrfMeta  = document.querySelector('meta[name="csrf-token"]');
        var paramMeta = document.querySelector('meta[name="csrf-param"]');

        if (csrfMeta && paramMeta) {
          fd.append(
            paramMeta.getAttribute('content'), // Nombre del parámetro (_csrf)
            csrfMeta.getAttribute('content')   // Valor del token CSRF
          );
          console.log(
            'CSRF Token adjuntado:',
            paramMeta.getAttribute('content'),
            csrfMeta.getAttribute('content')
          );
        } else {
          console.error("Meta tags CSRF no encontrados. La subida fallará con error 400.");
          reject("Token CSRF no encontrado en la página. Contacte al administrador.");
          return;
        }

        console.log('Iniciando subida a:', window.MEDIA_UPLOAD_URL);
        console.log('FormData a enviar (sin incluir datos binarios):');
        for (var pair of fd.entries()) {
          if (pair[1] instanceof File) {
            console.log(
              pair[0] + ': [File Object - Name: ' +
              pair[1].name + ', Size: ' + pair[1].size + ', Type: ' + pair[1].type + ']'
            );
          } else {
            console.log(pair[0] + ': ' + pair[1]);
          }
        }

        // 2) Crear el XMLHttpRequest para reportar progreso (opcional) y manejar respuesta
        var xhr = new XMLHttpRequest();
        xhr.open('POST', window.MEDIA_UPLOAD_URL, true);
        xhr.withCredentials = true;

        // Si quieres reportar progreso en TinyMCE 6, se puede usar xhr.upload.onprogress.
        // TinyMCE 6 asume que la Promesa no se resuelve hasta el 100%,
        // así que no debemos resolver antes de que acabe la subida.
        xhr.upload.onprogress = function(e) {
          if (e.lengthComputable) {
            // TinyMCE Da por sentado que esto controla internamente la barra de progreso
            // (no es estrictamente necesario invocar callback aquí, porque usamos Promesa).
            console.log('Progreso de subida:', Math.round((e.loaded / e.total) * 100) + '%');
          }
        };

        // Evento: carga completa
        xhr.onload = function() {
          if (xhr.status < 200 || xhr.status >= 300) {
            var text = xhr.responseText;
            console.error(
              'Error en la subida (HTTP ' + xhr.status + '). ' +
              'StatusText: ' + xhr.statusText
            );
            console.error(
              'Cuerpo de la respuesta del servidor (error ' +
              xhr.status + '):', text
            );
            var errorMessage = 'Error HTTP ' + xhr.status + ': ' + xhr.statusText + '.';
            try {
              var jsonError = JSON.parse(text);
              if (jsonError && jsonError.error && jsonError.error.message) {
                errorMessage = jsonError.error.message;
              } else if (jsonError && jsonError.message) {
                errorMessage = jsonError.message;
              } else if (text && text.length < 300) {
                errorMessage = text;
              }
            } catch (e) {
              if (text && text.length < 300) {
                errorMessage = text;
              }
              console.warn('No se pudo parsear el error como JSON:', e);
            }
            reject(errorMessage);
            return;
          }

          // Intentar parsear la respuesta como JSON
          try {
            var json = JSON.parse(xhr.responseText);
            console.log('Respuesta JSON del servidor (exitosa):', json);

            if (json.location) {
              // Resolvemos la Promesa con la URL final que inserta TinyMCE en <img>
              resolve(json.location);
            } else {
              var msg = 'Respuesta JSON inválida (sin "location").';
              if (json.error && json.error.message) msg = json.error.message;
              else if (json.message) msg = json.message;
              else msg += ' Contenido: ' + JSON.stringify(json);

              console.error(msg, json);
              reject(msg);
            }
          } catch (e) {
            console.error('No se pudo parsear la respuesta como JSON:', e);
            reject('Error al parsear JSON: ' + e.message);
          }
        };

        // Evento: error de red
        xhr.onerror = function() {
          console.error('Error de red durante la subida.');
          reject('Error de red durante la subida.');
        };

        // 3) Enviar el FormData
        xhr.send(fd);
      });
    },

    /**
     * 2) registerGallery: añade el botón “browsegallery” a TinyMCE
     *    (Sin cambios desde tu versión original).
     */
    registerGallery: function(editor) {
      editor.ui.registry.addButton('browsegallery', {
        icon: 'gallery',
        tooltip: 'Explorar galería',
        onAction: function() {
          fetch(window.MEDIA_BROWSE_URL, { credentials: 'same-origin' })
            .then(function(res) {
              return res.json();
            })
            .then(function(images) {
              var html = '<div style="display:grid;grid-template-columns:repeat(auto-fill,80px);gap:8px;">'
                       + images.map(function(img) {
                           return '<div data-url="' + img.value + '" style="cursor:pointer;text-align:center;">'
                                +   '<img src="' + img.value + '" style="max-width:80px;max-height:80px;margin-bottom:4px;" />'
                                +   '<small style="font-size:10px;">' + img.text + '</small>'
                                + '</div>';
                         }).join('')
                       + '</div>';

              editor.windowManager.open({
                title: 'Galería de imágenes',
                body: { type: 'panel', items: [{ type: 'htmlpanel', html: html }] },
                buttons: [{ type: 'cancel', text: 'Cerrar' }]
              });

              setTimeout(function() {
                document.querySelectorAll('[data-url]').forEach(function(el) {
                  el.onclick = function() {
                    editor.insertContent('<img src="' + el.dataset.url + '" />');
                    editor.windowManager.close();
                  };
                });
              }, 100);
            })
            .catch(function(err) {
              editor.notificationManager.open({ text: 'Error al cargar galería', type: 'error' });
              console.error(err);
            });
        }
      });
    }

    // Si en algún momento necesitas registrar tu plugin 'editimageplugin',
    // añádelo aquí con tinymce.PluginManager.add('editimageplugin', registerEditImage).
  };
})(window, document);