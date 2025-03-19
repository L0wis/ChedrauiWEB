<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atención al Cliente</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center">Atención al Cliente</h1>
        <p class="text-center">¡Estamos aquí para ayudarte! Selecciona una opción o completa el formulario para contactarnos.</p>
        
        <!-- Base de Conocimientos (FAQs) -->
        <section id="faqs" class="mb-5">
            <h2>Preguntas Frecuentes</h2>
            <div class="accordion" id="faqsAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            ¿Cómo realizo un seguimiento de mi pedido?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqsAccordion">
                        <div class="accordion-body">
                            Puedes realizar el seguimiento ingresando a tu cuenta y seleccionando la opción "Mis pedidos".
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            ¿Puedo cambiar mi información de contacto?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqsAccordion">
                        <div class="accordion-body">
                            Sí, ve a la sección "Mi perfil" en tu cuenta para actualizar tus datos.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nueva sección: ¿No encontraste tu problema? -->
            <div class="mt-4">
                <h3>¿No encontraste tu problema?</h3>
                <p>Si no encontraste la respuesta que buscabas, no te preocupes, ¡estamos aquí para ayudarte!</p>
                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#formularioContacto">
                    Escríbenos
                </button>

                <!-- Formulario colapsado -->
                <div class="collapse mt-3" id="formularioContacto">
                    <h4>Formulario de Contacto</h4>
                    <form action="procesar_contacto.php" method="post" class="mt-3">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="correo" name="correo" required>
                        </div>
                        <div class="mb-3">
                            <label for="mensaje" class="form-label">Mensaje</label>
                            <textarea class="form-control" id="mensaje" name="mensaje" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Enviar</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- Opciones de Contacto Directo -->
        <section id="contacto_directo" class="mb-5">
            <h2>Opciones de Contacto Directo</h2>
            <ul>
                <li><strong>Correo electrónico:</strong> soporte@ejemplo.com</li>
                <li><strong>Teléfono:</strong> +1 800 123 4567</li>
                <li><strong>Dirección:</strong> Calle Principal 123, Ciudad, País</li>
            </ul>
        </section>

        <!-- Encuesta de Satisfacción -->
        <section id="encuesta" class="mb-5">
            <h2>Encuesta de Satisfacción</h2>
            <p>Cuéntanos tu experiencia:</p>
            <form action="procesar_encuesta.php" method="post">
                <div class="mb-3">
                    <label class="form-label">Califica nuestro servicio:</label>
                    <select class="form-select" name="calificacion" required>
                        <option value="5">Excelente</option>
                        <option value="4">Bueno</option>
                        <option value="3">Regular</option>
                        <option value="2">Malo</option>
                        <option value="1">Terrible</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="comentarios" class="form-label">Comentarios adicionales:</label>
                    <textarea class="form-control" id="comentarios" name="comentarios" rows="4"></textarea>
                </div>
                <button type="submit" class="btn btn-success">Enviar Encuesta</button>
            </form>
        </section>

        <!-- Seguridad y Privacidad -->
        <section id="privacidad" class="mb-5">
            <h2>Seguridad y Privacidad</h2>
            <p>Tu información está protegida. Consulta nuestra <a href="politicas.php">Política de Privacidad</a>.</p>
        </section>

        <!-- Horarios de Atención -->
        <section id="horarios" class="mb-5">
            <h2>Horarios de Atención</h2>
            <p>Lunes a Viernes: 9:00 AM - 6:00 PM</p>
            <p>Sábados: 10:00 AM - 2:00 PM</p>
        </section>

        <!-- Feedback para la Empresa -->
        <section id="feedback" class="mb-5">
            <h2>Feedback para la Empresa</h2>
            <p>¿Tienes sugerencias? Escríbenos a <a href="mailto:feedback@ejemplo.com">feedback@ejemplo.com</a>.</p>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
