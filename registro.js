document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
    if (!form) return;

    // === Cargar SweetAlert si no existe ===
    if (typeof Swal === "undefined") {
        const script = document.createElement("script");
        script.src = "https://cdn.jsdelivr.net/npm/sweetalert2@11";
        document.head.appendChild(script);

        const style = document.createElement("link");
        style.rel = "stylesheet";
        style.href = "https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css";
        document.head.appendChild(style);

        script.onload = init;
    } else {
        init();
    }

    function init() {

        // =====================================================
        // 🧹 LIMPIEZA DE COSAS VIEJAS (divs de error y estilos)
        // =====================================================
        document.querySelectorAll(".mensaje-error").forEach(el => el.remove());

        const styleFix = document.createElement("style");
        styleFix.textContent = `
            .entrada-invalida,
            .entrada-valida {
                background: white !important;
                border-color: #ccc !important;
                box-shadow: none !important;
            }
            .mensaje-error {
                display: none !important;
            }
            input, select {
                margin-bottom: 5px !important;
            }
        `;
        document.head.appendChild(styleFix);

        // =====================================================
        // 🔒 BLOQUEO DE CARACTERES
        // =====================================================

        const inputNombre   = form.querySelector('[name="nombre"]');
        const inputApellido = form.querySelector('[name="apellido"]');
        const inputCedula   = form.querySelector('[name="cedula"]');

        // Solo letras (incluye acentos y espacios)
        function soloLetras(e) {
            if (!/^[A-Za-zÀ-ÿ\s]$/.test(e.key)) {
                e.preventDefault();
            }
        }

        // Solo números
        function soloNumeros(e) {
            if (!/[0-9]/.test(e.key)) {
                e.preventDefault();
            }
        }

        if (inputNombre)   inputNombre.addEventListener("keypress", soloLetras);
        if (inputApellido) inputApellido.addEventListener("keypress", soloLetras);
        if (inputCedula)   inputCedula.addEventListener("keypress", soloNumeros);

        // =====================================================
        // 🎯 VALIDACIÓN DEL FORMULARIO (SIN ENVIAR AÚN)
        // =====================================================
        function validar() {
            const nombre   = inputNombre.value.trim();
            const apellido = inputApellido.value.trim();
            const cedula   = inputCedula.value.trim();
            const correo   = form.querySelector('[name="email"]').value.trim();
            const carrera  = form.querySelector('[name="carrera"]').value;
            const pass     = form.querySelector('[name="password"]').value;
            const pass2    = form.querySelector('[name="repeat_password"]').value;

            let errores = [];

            if (!/^[A-Za-zÀ-ÿ\s]{3,40}$/.test(nombre)) {
                errores.push("El nombre debe contener solo letras y mínimo 3 caracteres.");
            }
            if (!/^[A-Za-zÀ-ÿ\s]{3,40}$/.test(apellido)) {
                errores.push("El apellido debe contener solo letras y mínimo 3 caracteres.");
            }
            if (!/^\d{6,10}$/.test(cedula)) {
                errores.push("La cédula debe contener entre 6 y 10 números.");
            }
            if (!/^[A-Za-z0-9_.+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/.test(correo)) {
                errores.push("Correo electrónico inválido.");
            }
            if (carrera === "") {
                errores.push("Debe seleccionar una carrera.");
            }
            if (pass.length < 8 || pass.length > 20) {
                errores.push("La contraseña debe tener entre 8 y 20 caracteres.");
            }
            if (pass !== pass2) {
                errores.push("Las contraseñas no coinciden.");
            }

            if (errores.length > 0) {
                Swal.fire({
                    title: "❌ Errores en el formulario",
                    html: errores.map(e => `<p style="text-align:left; margin:6px 0;">• ${e}</p>`).join(""),
                    icon: "error",
                    confirmButtonColor: "#d33",
                });
                return false;
            }

            // Si no hay errores, validación OK
            return true;
        }

        // =====================================================
        // 🚀 CONTROL DEL ENVÍO + TIMER DE 2 SEGUNDOS
        // =====================================================
        form.addEventListener("submit", (e) => {
            e.preventDefault(); // SIEMPRE detenemos el envío primero

            if (!validar()) {
                // Si hay errores, no enviamos el formulario
                return;
            }

            // Si todo está bien, mostramos el mensaje 2 segundos
            Swal.fire({
                title: "✔ Registro exitoso",
                text: "Estamos procesando tu registro...",
                icon: "success",
                timer: 2000,          // 2 segundos en la página
                showConfirmButton: false,
                timerProgressBar: true
            }).then(() => {
                // Después de los 2 segundos, ahora sí enviamos el formulario
                form.submit();
                // PHP recibirá el POST y hará:
                // header("Location: login.php");
            });
        });
    }
});
