<!DOCTYPE html>
<html>

<head>
    <title>Curriculum Vitae - {{ entidad.ent_nombre }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="{{ ruta }}/recursos/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.js"></script>
    <script>
        function imprimirCurriculumPDF() {
            console.log("Clic en el botón de imprimir PDF");
            const element = document.querySelector('.container-cv');
            const options = {
                filename: 'CV_{{ entidad.ent_nombre }}.pdf',
                margin: [0.2, 0.2, 0.2, 0.2],
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            };

            const imprimirBtn = document.getElementById("imprimirBtnPdf");
            imprimirBtn.style.display = "none";

            html2pdf().set(options).from(element).save();

            imprimirBtn.style.display = "block";
        }
    </script>
</head>

<body>
    <button id="imprimirBtnPdf" onclick="imprimirCurriculumPDF()">Imprimir en PDF</button>
    <div class="container-externo">
        <div class="container-cv">
            <div class="container-cabecera">
                <h2>{{ entidad.ent_nombre }}</h2>
                <p class="estudios">{{ entidad.ent_resumen_perfil }}</p>
                <p class="estudios">{{ entidad.ent_resumen_estudios }}</p>
                <p class="enlaces"><a href="tel:{{ entidad.ent_telefono }}">{{ entidad.ent_telefono }}</a> – <a href="mailto:{{ entidad.ent_correo }}">{{ entidad.ent_correo }}</a></p>
                <p class="enlaces"><a href="https://www.linkedin.com/in/{{ entidad.ent_linkedin }}">linkedin.com/{{ entidad.ent_linkedin }}</a> - <a href="{{ entidad.ent_sitio_web }}">{{ entidad.ent_sitio_web }}</a></p>
            </div>
            <div class="container-cuerpo">
                <div class="perfil-titulo">Perfil Profesional</div>
                <p class="perfil-contenido">{{ entidad.ent_perfil }}</p>

                <div class="experiencia-profesional">
                    <div class="perfil-experiencia">Experiencia Profesional</div>
                    {% for experiencia in experiencias %}
                        <div class="bloque-experiencia">
                            <p class="experiencia-titulo">{{ experiencia.exp_cargo }} - {{ experiencia.exp_empresa }} - ({{ experiencia.exp_fecha_inicio }} - {{ experiencia.exp_fecha_fin | default('A la fecha') }})</p>
                            <div class="detalle-experiencia">{{ experiencia.exp_descripcion }}</div>
                        </div>
                        <hr>
                    {% endfor %}
                </div>

                <div class="formacion-academica">
                    <div class="perfil-experiencia">Mi formación académica</div>
                    {% for formacion in formaciones %}
                        <div class="detalle-experiencia">
                            <div class="nombre-experiencia">{{ formacion.for_institucion }}</div>
                            <div class="columna-medio">{{ formacion.for_fecha_inicio }} - {{ formacion.for_fecha_fin }}</div>
                            <div class="columna-derecha">{{ formacion.for_grado_titulo }}</div>
                        </div>
                    {% endfor %}
                </div>

                <div class="certificaciones">
                    <div class="perfil-experiencia">Certificaciones</div>
                    {% for certificacion in certificaciones %}
                        <div class="detalle-experiencia">
                            <div class="nombre-experiencia">{{ certificacion.for_institucion }}</div>
                            <div class="columna-medio">{{ certificacion.for_fecha_fin }}</div>
                            <div class="columna-derecha">{{ certificacion.for_grado_titulo }} - {{ certificacion.for_codigo_validacion }}</div>
                        </div>
                    {% endfor %}
                </div>

                <div class="cursos">
                    <div class="perfil-experiencia">Cursos</div>
                    {% for curso in cursos %}
                        <div class="detalle-experiencia">
                            <div class="nombre-experiencia">{{ curso.for_institucion }}</div>
                            <div class="columna-derecha">{{ curso.for_grado_titulo }}</div>
                        </div>
                    {% endfor %}
                </div>

                <div class="perfil-experiencia">Habilidades</div>
                <p class="habilidades">{{ habilidades | join(', ') }}</p>

                <div class="perfil-experiencia">Herramientas</div>
                <p class="herramientas">{{ herramientas | join(', ') }}</p>

                <div class="perfil-experiencia">Idiomas</div>
                <p class="habilidades">{{ entidad.ent_idiomas }}</p>

                <div class="copyright" style="width:100%; text-align: center; font-size:10px;">
                    Actualización: {{ entidad.ent_fecha_actualizacion }}
                </div>
            </div>
        </div>
    </div>
</body>

</html>