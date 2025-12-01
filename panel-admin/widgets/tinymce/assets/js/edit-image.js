;(function(window, document) {
    console.log('[EDIT-IMAGE.JS Opción B] Cargado. Apuntará a actionUpload.');

    function registerEditImage(editor) {
      editor.ui.registry.addMenuItem('editimage', {
        text: '✂️ Editar imagen',
        context: 'image',
        onAction: () => {
          const imgNode = editor.selection.getNode();
          if (!imgNode || imgNode.nodeName !== 'IMG') return;

          const styleAttr = imgNode.getAttribute('style') || '';
          console.log('[EDIT-IMAGE OpB] Estilo actual:', styleAttr);

          // Extraer filtros del styleAttribute (sin cambios respecto a antes)
          let filters = {
            grayscale: /grayscale\(100%\)/.test(styleAttr),
            brightness: 1, contrast: 1, sepia: 0, saturate: 1,
            hueRotate: 0, invert: false, blur: 0
          };
          const bMatch = styleAttr.match(/brightness\(([0-9.]+)\)/);
          if (bMatch) filters.brightness = parseFloat(bMatch[1]);
          const cMatch = styleAttr.match(/contrast\(([0-9.]+)\)/);
          if (cMatch) filters.contrast = parseFloat(cMatch[1]);
          const sepiaMatch = styleAttr.match(/sepia\(([0-9.]+)\)/);
          if (sepiaMatch) filters.sepia = parseFloat(sepiaMatch[1]);
          const saturateMatch = styleAttr.match(/saturate\(([0-9.]+)\)/);
          if (saturateMatch) filters.saturate = parseFloat(saturateMatch[1]);
          const hueMatch = styleAttr.match(/hue-rotate\(([0-9.]+)deg\)/);
          if (hueMatch) filters.hueRotate = parseFloat(hueMatch[1]);
          if (/invert\(1\)/.test(styleAttr) || /invert\(100%\)/.test(styleAttr)) filters.invert = true;
          const blurMatch = styleAttr.match(/blur\(([0-9.]+)px\)/);
          if (blurMatch) filters.blur = parseFloat(blurMatch[1]);
          console.log('[EDIT-IMAGE OpB] Filtros parseados para modal:', JSON.parse(JSON.stringify(filters)));

          // Extraer clip-path previo (si existe)
          let parsedTop = 0, parsedRight = 0, parsedBottom = 0, parsedLeft = 0;
          const clipMatch = styleAttr.match(/clip-path:\s*inset\(([^)]+)\)/);
          if (clipMatch) {
            const parts = clipMatch[1].replace(/px/g, '').split(/\s+/).map(n => parseFloat(n));
            if (parts.length === 1) {
              parsedTop = parsedRight = parsedBottom = parsedLeft = parts[0];
            } else if (parts.length === 2) {
              parsedTop = parsedBottom = parts[0];
              parsedLeft = parsedRight = parts[1];
            } else if (parts.length === 3) {
              parsedTop = parts[0];
              parsedLeft = parsedRight = parts[1];
              parsedBottom = parts[2];
            } else if (parts.length === 4) {
              parsedTop = parts[0];
              parsedRight = parts[1];
              parsedBottom = parts[2];
              parsedLeft = parts[3];
            }
          }
          console.log('[EDIT-IMAGE OpB] Recorte (inset) parseado:', { top: parsedTop, right: parsedRight, bottom: parsedBottom, left: parsedLeft });

          // Construir modal
          const modal = document.createElement('div');
          modal.style.cssText = `
            position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(0,0,0,0.7); display:flex;
            align-items:center; justify-content:center; z-index:100000;
            padding: 20px; box-sizing: border-box;`;
          const inner = document.createElement('div');
          inner.className = 'image-editor-inner-content';
          modal.appendChild(inner);

          const imagePreviewArea = document.createElement('div');
          imagePreviewArea.className = 'image-preview-area';
          inner.appendChild(imagePreviewArea);

          const controlsPanelArea = document.createElement('div');
          controlsPanelArea.className = 'controls-panel-area';
          inner.appendChild(controlsPanelArea);

          const actionButtonsArea = document.createElement('div');
          actionButtonsArea.className = 'action-buttons-area';
          inner.appendChild(actionButtonsArea);

          const preview = document.createElement('img');
          preview.src = imgNode.src;
          imagePreviewArea.appendChild(preview);

          let currentCropData = {};

          // --- MODIFICACIÓN PRINCIPAL: DESACTIVAR AUTO-CROP ---
          const cropper = new Cropper(preview, {
            viewMode: 1,
            background: false,
            responsive: true,
            autoCrop: false,              // <--- bloquea el recorte inicial
            restore: true,
            zoomOnWheel: false,
            ready() {
              // Si existe clip-path previo, entonces preconfiguramos el recorte.
              if (clipMatch) {
                const initialWidth = preview.naturalWidth - parsedLeft - parsedRight;
                const initialHeight = preview.naturalHeight - parsedTop - parsedBottom;
                if (initialWidth > 0 && initialHeight > 0) {
                  cropper.setData({
                    x: parsedLeft,
                    y: parsedTop,
                    width: initialWidth,
                    height: initialHeight
                  });
                } else {
                  console.warn("[EDIT-IMAGE OpB] Clip-path inválido. Cropper usará vista por defecto sin recorte.");
                }
              }
              // No ejecutar cropper.reset() ni cropper.crop() si no hay clip-path.
              currentCropData = cropper.getData(true);
              console.log('[EDIT-IMAGE OpB] CropData inicial (sin recorte):', JSON.parse(JSON.stringify(currentCropData)));
              updateFiltering();
            },
            crop(event) {
              currentCropData = {
                x: Math.round(event.detail.x),
                y: Math.round(event.detail.y),
                width: Math.round(event.detail.width),
                height: Math.round(event.detail.height)
              };
            }
          });

          function addControl(labelText, prop, isToggle = false, opts = {}) {
            const controlRow = document.createElement('div');
            controlRow.className = 'control-row';
            const label = document.createElement('label');
            label.textContent = labelText;
            controlRow.appendChild(label);

            const inputContainer = document.createElement('div');
            let input;
            if (isToggle) {
              input = document.createElement('button');
              input.textContent = filters[prop] ? 'Desactivar' : 'Activar';
              input.onclick = () => {
                filters[prop] = !filters[prop];
                input.textContent = filters[prop] ? 'Desactivar' : 'Activar';
                updateFiltering();
              };
              inputContainer.appendChild(input);
            } else {
              input = document.createElement('input');
              input.type = 'range';
              input.min = opts.min;
              input.max = opts.max;
              input.step = opts.step;
              input.value = filters[prop];
              inputContainer.appendChild(input);
              const valueDisplay = document.createElement('span');
              valueDisplay.className = 'value-display';
              valueDisplay.textContent = ` ${input.value}` + (opts.unit || '');
              inputContainer.appendChild(valueDisplay);
              input.oninput = () => {
                filters[prop] = parseFloat(input.value);
                valueDisplay.textContent = ` ${input.value}` + (opts.unit || '');
                updateFiltering();
              };
            }
            controlRow.appendChild(inputContainer);
            controlsPanelArea.appendChild(controlRow);
          }

          addControl('Grayscale', 'grayscale', true);
          addControl('Brillo', 'brightness', false, { min: 0.2, max: 2, step: 0.05, default: 1 });
          addControl('Contraste', 'contrast', false, { min: 0.2, max: 2, step: 0.05, default: 1 });
          addControl('Sepia', 'sepia', false, { min: 0, max: 1, step: 0.01, default: 0 });
          addControl('Saturar', 'saturate', false, { min: 0, max: 3, step: 0.05, default: 1 });
          addControl('Tono (Hue)', 'hueRotate', false, { min: 0, max: 360, step: 1, default: 0, unit: 'deg' });
          addControl('Invertir', 'invert', true);
          addControl('Desenfoque (Blur)', 'blur', false, { min: 0, max: 10, step: 0.1, default: 0, unit: 'px' });

          function updateFiltering() {
            const arr = [];
            if (filters.grayscale) arr.push('grayscale(100%)');
            if (filters.brightness !== 1) arr.push(`brightness(${filters.brightness})`);
            if (filters.contrast !== 1) arr.push(`contrast(${filters.contrast})`);
            if (filters.sepia > 0) arr.push(`sepia(${filters.sepia})`);
            if (filters.saturate !== 1) arr.push(`saturate(${filters.saturate})`);
            if (filters.hueRotate !== 0) arr.push(`hue-rotate(${filters.hueRotate}deg)`);
            if (filters.invert) arr.push('invert(100%)');
            if (filters.blur > 0) arr.push(`blur(${filters.blur}px)`);
            const filterString = arr.length ? arr.join(' ') : 'none';

            const canvasImg = imagePreviewArea.querySelector('.cropper-canvas img');
            if (canvasImg && canvasImg.style) {
              canvasImg.style.filter = filterString;
              canvasImg.style.transform = 'translateZ(0)';
            }
            const viewBoxImg = imagePreviewArea.querySelector('.cropper-view-box img');
            if (viewBoxImg && viewBoxImg.style) {
              viewBoxImg.style.filter = filterString;
              viewBoxImg.style.transform = 'translateZ(0)';
            }
          }

          const btnReset = document.createElement('button');
          btnReset.textContent = 'Reset';
          btnReset.className = 'secondary';
          btnReset.onclick = () => {
            console.log('[EDIT-IMAGE OpB] --- Botón RESET clicado ---');
            filters.grayscale = false;
            filters.brightness = 1;
            filters.contrast = 1;
            filters.sepia = 0;
            filters.saturate = 1;
            filters.hueRotate = 0;
            filters.invert = false;
            filters.blur = 0;
            cropper.reset();
            cropper.crop();
            controlsPanelArea.querySelectorAll('.control-row').forEach(cRow => {
              const labelEl = cRow.querySelector('label');
              const inputEl = cRow.querySelector('input[type="range"], button');
              const displaySpan = cRow.querySelector('.value-display');
              if (!labelEl || !inputEl) return;
              let propName = '';
              if (labelEl.textContent.includes('Brillo')) propName = 'brightness';
              else if (labelEl.textContent.includes('Contraste')) propName = 'contrast';
              else if (labelEl.textContent.includes('Sepia')) propName = 'sepia';
              else if (labelEl.textContent.includes('Saturar')) propName = 'saturate';
              else if (labelEl.textContent.includes('Tono')) propName = 'hueRotate';
              else if (labelEl.textContent.includes('Desenfoque')) propName = 'blur';
              else if (labelEl.textContent.includes('Grayscale')) propName = 'grayscale';
              else if (labelEl.textContent.includes('Invertir')) propName = 'invert';

              if (propName && filters[propName] !== undefined) {
                if (inputEl.type === 'range') {
                  inputEl.value = filters[propName];
                  if (displaySpan) {
                    let unit = '';
                    if (propName === 'hueRotate') unit = 'deg';
                    else if (propName === 'blur') unit = 'px';
                    displaySpan.textContent = ` ${inputEl.value}` + unit;
                  }
                } else if (inputEl.tagName === 'BUTTON') {
                  inputEl.textContent = filters[propName] ? 'Desactivar' : 'Activar';
                }
              }
            });
            updateFiltering();
          };
          actionButtonsArea.appendChild(btnReset);

          const btnUse = document.createElement('button');
          btnUse.textContent = 'Usar imagen';
          btnUse.className = 'primary';
          btnUse.onclick = () => {
            console.log('[EDIT-IMAGE OpB] --- Botón "Usar imagen" CLICADO ---');

            btnUse.disabled = true;
            btnReset.disabled = true;

            let croppedCanvas = cropper.getCroppedCanvas();
            if (!croppedCanvas) {
              console.error('[EDIT-IMAGE OpB] No se pudo obtener el canvas recortado.');
              alert('Error al procesar la imagen recortada.');
              btnUse.disabled = false;
              btnReset.disabled = false;
              return;
            }

            // Aplicar filtros CSS al canvas antes de convertir a Blob
            const arr = [];
            if (filters.grayscale) arr.push('grayscale(100%)');
            if (filters.brightness !== 1) arr.push(`brightness(${filters.brightness})`);
            if (filters.contrast !== 1) arr.push(`contrast(${filters.contrast})`);
            if (filters.sepia > 0) arr.push(`sepia(${filters.sepia})`);
            if (filters.saturate !== 1) arr.push(`saturate(${filters.saturate})`);
            if (filters.hueRotate !== 0) arr.push(`hue-rotate(${filters.hueRotate}deg)`);
            if (filters.invert) arr.push('invert(100%)');
            if (filters.blur > 0) arr.push(`blur(${filters.blur}px)`);
            const filterString = arr.length ? arr.join(' ') : 'none';

            if (filterString !== 'none') {
              const offCanvas = document.createElement('canvas');
              offCanvas.width = croppedCanvas.width;
              offCanvas.height = croppedCanvas.height;
              const offCtx = offCanvas.getContext('2d');
              offCtx.filter = filterString;
              offCtx.drawImage(croppedCanvas, 0, 0);
              croppedCanvas = offCanvas;
            }

            console.log('[EDIT-IMAGE OpB] Datos de recorte (de Cropper) a enviar:', JSON.parse(JSON.stringify(currentCropData)));

            croppedCanvas.toBlob(function(blob) {
              if (!blob) {
                console.error('[EDIT-IMAGE OpB] No se pudo convertir el canvas a Blob.');
                alert('Error al generar la imagen para enviar.');
                btnUse.disabled = false;
                btnReset.disabled = false;
                return;
              }

              const formData = new FormData();
              const originalFileNameFromSrc = imgNode.src.substring(imgNode.src.lastIndexOf('/') + 1) || 'edited_image.png';
              const timestamp = Date.now();
              const newFileName = `cropped_${timestamp}_${originalFileNameFromSrc.split('?')[0]}`;
              formData.append('file', blob, newFileName);

              const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
              const csrfParamMeta = document.querySelector('meta[name="csrf-param"]');
              let csrfToken = null;
              let csrfParam = '_csrf';
              if (csrfTokenMeta) csrfToken = csrfTokenMeta.getAttribute('content');
              if (csrfParamMeta) csrfParam = csrfParamMeta.getAttribute('content');
              if (csrfToken && csrfParam) {
                formData.append(csrfParam, csrfToken);
                console.log('[EDIT-IMAGE OpB] CSRF Token añadido a FormData:', csrfParam, csrfToken);
              } else {
                console.warn('[EDIT-IMAGE OpB] No se encontraron meta tags CSRF.');
              }

              const targetUrl = window.MEDIA_UPLOAD_URL;
              if (!targetUrl) {
                console.error("[EDIT-IMAGE OpB] window.MEDIA_UPLOAD_URL no está definida! No se puede subir.");
                alert("Error de configuración: URL de subida no definida.");
                btnUse.disabled = false;
                btnReset.disabled = false;
                return;
              }
              console.log('[EDIT-IMAGE OpB] Enviando datos (recortados) a actionUpload:', targetUrl);

              fetch(targetUrl, {
                method: 'POST',
                body: formData
              })
                .then(response => {
                  if (!response.ok) {
                    console.error('[EDIT-IMAGE OpB] Error en el servidor. Status:', response.status, response.statusText);
                    return response.text().then(text => {
                      console.error('[EDIT-IMAGE OpB] Cuerpo de la respuesta del servidor (error):', text);
                      let errorMessage = `Error HTTP ${response.status}: ${response.statusText}.`;
                      try {
                        const jsonError = JSON.parse(text);
                        if (jsonError && jsonError.error && jsonError.error.message) errorMessage = jsonError.error.message;
                        else if (jsonError && jsonError.message) errorMessage = jsonError.message;
                        else if (text && text.length > 0 && text.length < 300) errorMessage = text;
                      } catch (_) {
                        if (text && text.length > 0 && text.length < 300) errorMessage = text;
                      }
                      throw new Error(errorMessage);
                    });
                  }
                  return response.json();
                })
                .then(data => {
                  console.log('[EDIT-IMAGE OpB] Respuesta del servidor (actionUpload exitosa):', data);
                  if (data && data.location) {
                    // Forzar recarga con cache-buster
                    const nuevaUrl = data.location + '?v=' + Date.now();

                    editor.undoManager.transact(() => {
                      editor.dom.setAttrib(imgNode, 'src', nuevaUrl);
                      editor.dom.setAttrib(imgNode, 'style', null);
                    });
                    editor.execCommand('mceRepaint');
                    editor.nodeChanged();
                    editor.fire('change');
                  } else {
                    let em = 'Respuesta de actionUpload no contiene "location" o no es JSON válido.';
                    if (data && data.error && data.error.message) em = data.error.message;
                    else if (data && data.message) em = data.message;
                    throw new Error(em);
                  }
                })
                .catch(error => {
                  console.error('[EDIT-IMAGE OpB] Error al procesar imagen con actionUpload:', error);
                  alert(`Error al guardar la imagen editada (OpB): ${error.message}. Revisa la consola.`);
                })
                .finally(() => {
                  btnUse.disabled = false;
                  btnReset.disabled = false;
                  if (cropper) cropper.destroy();
                  if (modal && modal.parentNode) document.body.removeChild(modal);
                });
            }, 'image/png');
          };
          actionButtonsArea.appendChild(btnUse);

          const btnCancel = document.createElement('button');
          btnCancel.textContent = 'Cancelar';
          btnCancel.className = 'secondary';
          btnCancel.onclick = () => {
            console.log('[EDIT-IMAGE OpB] --- Botón CANCELAR clicado ---');
            if (cropper) cropper.destroy();
            if (modal && modal.parentNode) document.body.removeChild(modal);
          };
          actionButtonsArea.appendChild(btnCancel);

          document.body.appendChild(modal);
          modal.offsetHeight;
        }
      });
    }

    function initializeEditImage() {
      if (window.tinymce && tinymce.PluginManager) {
        tinymce.PluginManager.add('editimageplugin', registerEditImage);
      }
      window.TinyMCELogic = window.TinyMCELogic || {};
      window.TinyMCELogic.registerEditImage = registerEditImage;
      console.log('[EDIT-IMAGE OpB] Plugin y función registerEditImage registrados en TinyMCELogic.');
    }

    if (window.tinymce) {
      initializeEditImage();
    } else {
      document.addEventListener('DOMContentLoaded', initializeEditImage);
    }
})(window, document);