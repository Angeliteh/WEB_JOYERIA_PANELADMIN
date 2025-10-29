<?php
// Este archivo se incluye en todas las páginas del admin para verificar autenticación
session_start();

// Si no está logueado, redirigir al login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Función para mostrar el header del admin
function showAdminHeader($pageTitle = 'Panel de Administración') {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $pageTitle ?> - Joyería Matt</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <style>
            body {
                background-color: #f8f9fa;
                font-family: 'Raleway', sans-serif;
            }
            .navbar-admin {
                background: linear-gradient(135deg, #D4A574 0%, #8B7355 100%);
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
            .navbar-brand {
                font-weight: 600;
                color: white !important;
            }
            .nav-link {
                color: rgba(255,255,255,0.9) !important;
                font-weight: 500;
            }
            .nav-link:hover {
                color: white !important;
            }
            .main-content {
                padding: 2rem 0;
            }
            .card {
                border: none;
                border-radius: 15px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            }
            .card-header {
                background: linear-gradient(135deg, #D4A574 0%, #8B7355 100%);
                color: white;
                border-radius: 15px 15px 0 0 !important;
                font-weight: 600;
            }
            .btn-primary {
                background: linear-gradient(135deg, #D4A574 0%, #8B7355 100%);
                border: none;
                border-radius: 8px;
            }
            .btn-primary:hover {
                background: linear-gradient(135deg, #C9A96E 0%, #7A6A4F 100%);
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-admin">
            <div class="container">
                <a class="navbar-brand" href="dashboard.php">
                    <i class="fas fa-gem"></i> Joyería Matt Admin
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="productos.php">
                                <i class="fas fa-gem"></i> Productos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="solicitudes.php">
                                <i class="fas fa-envelope-open-text"></i> Solicitudes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="gestor-imagenes.php">
                                <i class="fas fa-images"></i> Imágenes
                            </a>
                        </li>
                    </ul>
                    
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> <?= $_SESSION['admin_username'] ?? 'Admin' ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="../index.html" target="_blank">
                                    <i class="fas fa-eye"></i> Ver sitio web
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php">
                                    <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                                </a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        
        <div class="main-content">
            <div class="container">
    <?php
}

// Función para mostrar el footer del admin
function showAdminFooter() {
    ?>
            </div>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
}
?>
