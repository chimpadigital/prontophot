<?php
session_start();
include 'conexion/conectar.inc.php';
include_once 'inc/config.inc.php';
global $conectar;

// Obtener el parámetro status de la URL
$status = isset($_GET['status']) ? $_GET['status'] : 'error';
$payment_intent_id = isset($_GET['payment_intent_id']) ? $_GET['payment_intent_id'] : '';
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : '';

// Validar que el status sea válido
$status = in_array($status, ['exito', 'error']) ? $status : 'error';

// Si es éxito, limpiar el carrito
if ($status === 'exito') {
    unset($_SESSION['pronto']['cart']);
    unset($_SESSION['archivos']);
    unset($_SESSION['prontoFront']['envio']);
}

include 'header.php';
?>

<style>
    .status-container {
        min-height: 60vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    }

    .status-card {
        max-width: 600px;
        width: 100%;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        padding: 40px;
        text-align: center;
    }

    .status-icon {
        font-size: 80px;
        margin-bottom: 20px;
    }

    .status-icon.success {
        color: #28a745;
    }

    .status-icon.error {
        color: #dc3545;
    }

    .status-title {
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .status-message {
        font-size: 16px;
        color: #666;
        margin-bottom: 30px;
        line-height: 1.6;
    }

    .status-details {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 30px;
        text-align: left;
    }

    .status-details p {
        margin: 10px 0;
        font-size: 14px;
    }

    .status-details strong {
        color: #333;
    }

    .btn-action {
        margin: 10px 5px;
        padding: 12px 30px;
        font-size: 16px;
        border-radius: 5px;
    }

    .getnet-logo {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .getnet-logo img {
        max-width: 150px;
        opacity: 0.7;
    }
</style>

<div class="status-container">
    <div class="status-card">
        <?php if ($status === 'exito'): ?>
            <!-- Pago Exitoso -->
            <div class="status-icon success">
                <i class="fa fa-check-circle"></i>
            </div>
            <h1 class="status-title text-success">¡Pago Exitoso!</h1>
            <p class="status-message">
                Tu pago ha sido procesado correctamente a través de GetNet.
                Recibirás un correo electrónico con la confirmación de tu pedido en breve.
            </p>

            <?php if ($order_id): ?>
            <div class="status-details">
                <p><strong>Número de pedido:</strong> #<?php echo htmlspecialchars($order_id); ?></p>
                <?php if ($payment_intent_id): ?>
                <p><strong>ID de transacción:</strong> <?php echo htmlspecialchars($payment_intent_id); ?></p>
                <?php endif; ?>
                <p><strong>Estado:</strong> <span class="text-success">Confirmado</span></p>
            </div>
            <?php endif; ?>

            <div>
                <a href="index.php" class="btn btn-danger btn-action">
                    <i class="fa fa-home"></i> Volver al Inicio
                </a>
                <?php if (isset($_SESSION['prontoFront']['idcliente'])): ?>
                <a href="versesion.php" class="btn btn-outline-danger btn-action">
                    <i class="fa fa-list"></i> Ver mis Pedidos
                </a>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <!-- Pago con Error -->
            <div class="status-icon error">
                <i class="fa fa-times-circle"></i>
            </div>
            <h1 class="status-title text-danger">Error en el Pago</h1>
            <p class="status-message">
                Lo sentimos, no pudimos procesar tu pago a través de GetNet.
                Por favor, intenta nuevamente o contacta con nuestro equipo de soporte.
            </p>

            <?php if ($order_id): ?>
            <div class="status-details">
                <p><strong>Número de pedido:</strong> #<?php echo htmlspecialchars($order_id); ?></p>
                <?php if ($payment_intent_id): ?>
                <p><strong>ID de transacción:</strong> <?php echo htmlspecialchars($payment_intent_id); ?></p>
                <?php endif; ?>
                <p><strong>Estado:</strong> <span class="text-danger">Pendiente de pago</span></p>
            </div>
            <?php endif; ?>

            <p class="status-message" style="font-size: 14px;">
                <strong>Posibles causas:</strong><br>
                • Fondos insuficientes<br>
                • Datos incorrectos de la tarjeta<br>
                • Transacción rechazada por el banco<br>
                • Tiempo de sesión expirado
            </p>

            <div>
                <a href="checkout_1.php" class="btn btn-danger btn-action">
                    <i class="fa fa-refresh"></i> Intentar Nuevamente
                </a>
                <a href="index.php" class="btn btn-outline-danger btn-action">
                    <i class="fa fa-home"></i> Volver al Inicio
                </a>
            </div>
        <?php endif; ?>

        <!-- Logo GetNet -->
        <div class="getnet-logo">
            <p style="font-size: 12px; color: #999; margin-bottom: 10px;">Procesado por</p>
            <img src="images/getnet-logo.png" alt="GetNet" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
            <p style="font-size: 14px; color: #999; font-weight: 600; display: none;">GetNet</p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
