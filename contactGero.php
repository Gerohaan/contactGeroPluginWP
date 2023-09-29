<?php 
/* 
Plugin Name: ContactGero
Plugin URI: 
Description: Formulario de contacto simple para acercar clientes a tu negocio. Usa el shortcode siguiente en la página o sección donde deseas mostrar el formulario [contactGero]
Version: 0.0.1 
*/

function formulario_de_contacto_shortcode() {
    ob_start();
    ?>
    <div class="border border-primary p-4 rounded">
    <form method="post" action="">
        <label class="text-bold text-primary" for="nombres">Nombres:</label>
        <input type="text" name="nombres" id="nombres" class="form-control" required>

        <label class="text-bold text-primary mt-3" for="apellidos">Apellidos:</label>
        <input type="text" name="apellidos" id="apellidos" class="form-control" required>

        <label class="text-bold text-primary mt-3" for="rut">RUT Chileno:</label>
        <input pattern="\d{1,2}\.\d{3}\.\d{3}[-][0-9kK]{1}" required title="Ingrese un Rut válido (ejemplo: 12.345.678-9)" placeholder="Ejemplo: 12.345.678-9" type="text" name="rut" id="rut" class="form-control" >

        <label class="text-bold text-primary mt-3" for="telefono">Teléfono:</label>
        <input pattern="[0-9]{9,15}" required title="Ingrese un número de teléfono válido (de 9 a 15 dígitos)" type="tel" name="telefono" id="telefono" class="form-control">
         <div class="mt-4">
         <input class="form-control text-white bg-primary" color="primary" type="submit" value="Enviar" class="btn btn-primary">
         </div> 
    </form>
    </div>
    <?php
    return ob_get_clean();
}

function formCapture() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombres = $_POST['nombres'];
        $apellidos = $_POST['apellidos'];
        $rut = $_POST['rut'];
        $telefono = $_POST['telefono'];

        // Validar los campos
        if (empty($nombres) || empty($apellidos)) {
            echo '<p class="mensaje-deneged">Debes ingresar nombres y apellidos.</p>';
        }else
        if (empty($rut)){
            echo '<p class="mensaje-deneged">Debes ingresar RUT.</p>';
        }else 
        if (empty($telefono)){
            echo '<p class="mensaje-deneged">Debes ingresar telefono.</p>';
        }else 
        if (!preg_match('/^[a-zA-Z\s]+$/', $nombres) || !preg_match('/^[a-zA-Z\s]+$/', $apellidos)) {
            echo '<p class="mensaje-deneged">Los nombres y apellidos deben estar escritos de una forma corrécta.</p>';
            
        }else 
        if (strlen($nombres) > 50 || strlen($apellidos) > 50) {
            echo '<p class="mensaje-deneged">Los nombres y apellidos no deben tener una longitud mínima requerida.</p>';
        }else{
        
            // Enviar el correo electrónico al administrador
            $admin_email = get_option('admin_email');
            $subject = 'Quieren contactarse contigo!';
            $message = "Nombres: $nombres\nApellidos: $apellidos\nRUT: $rut\nTeléfono: $telefono";
            wp_mail($admin_email, $subject, $message);
            
            // Mostrar un mensaje de éxito después de enviar el formulario
            echo '<p class="mensaje-exito">¡Gracias por tu mensaje! Nos pondremos en contacto contigo pronto.</p>';
        }
    }
    ob_start();
    ?>
    <?php
    return ob_get_clean();
}

function formulario_de_contacto_enqueue_styles() {
    wp_enqueue_style('bootstrap', plugins_url('/admin/assets/css/bootstrap.min.css', __FILE__));
    wp_enqueue_style('formulario-de-contacto', plugins_url('/admin/assets/css/formulario-de-contacto.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'formulario_de_contacto_enqueue_styles');

add_action('wp_head', 'formCapture');
add_shortcode('contactGero', 'formulario_de_contacto_shortcode');

?>