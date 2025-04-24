<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú del Restaurante</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/styles.css">
    <script src="https://kit.fontawesome.com/ac562f7988.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="./img/mug-saucer-solid.svg">
</head>
<body>
    <?php
    $archivo = 'xml/menu.xml';
    if (file_exists($archivo)) {
        $menu = simplexml_load_file($archivo);

        $categorias = [];
        foreach($menu->plato as $plato) {
            $tipo = (string)$plato['tipo'];
            if(!in_array($tipo, $categorias)) {
                $categorias[] = $tipo;
            }
        }

        $iconosCategorias = [
            'lacteo' => '<i class="fas fa-cheese" title="Lácteo"></i>',
            'sin gluten' => '<i class="fas fa-bread-slice" title="Sin Gluten"></i>',
            'vegano' => '<i class="fas fa-leaf" title="Vegano"></i>',
            'vegetariano' => '<i class="fas fa-carrot" title="Vegetariano"></i>',
            'picante' => '<i class="fas fa-pepper-hot" title="Picante"></i>',
            'carne' => '<i class="fas fa-drumstick-bite" title="Carne"></i>',
            'marisco' => '<i class="fas fa-shrimp" title="Marisco"></i>',
            'pescado' => '<i class="fas fa-fish" title="Pescado"></i>',
        ];
    } else {
        die('<div class="error">Error: No se pudo cargar el archivo XML del menú</div>');
    }
    ?>

    <header class="menu-header">
        <h1 class="menu-title">Bar Tech</h1>
        <p class="menu-subtitle">Tu Bar de confianza</p>
    </header>

    <main class="menu-main">
        <div class="menu-columns">
            <?php
            $totalCategorias = count($categorias);
            if($totalCategorias > 0) {
                for($i = 0; $i < $totalCategorias; $i++) {
                    echo '<section class="menu-category '.($i === $totalCategorias - 1 ? 'full-width' : '').'">';
                    echo '<h2 class="category-title">'.$categorias[$i].'</h2>';

                    foreach($menu->plato as $plato) {
                        if((string)$plato['tipo'] == $categorias[$i]) {
                            echo '<article class="menu-item">';
                            echo '<div class="item-header">';
                            echo '<h3 class="item-name">'.$plato->nombre.'</h3>';
                            echo '<span class="item-price">'.$plato->precio.' €</span>';
                            echo '</div>';

                            if(!empty((string)$plato->calorias)) {
                                echo '<div class="item-calories">';
                                echo '<span class="calories-label">Calorías:</span> ';
                                echo '<span class="calories-value">'.$plato->calorias.' kcal</span>';
                                echo '</div>';
                            }

                            if(isset($plato->ingredientes->categoria)) {
                                echo '<div class="item-categories">';
                                echo '<span class="categories-label">Categorías:</span> ';
                                $iconos = [];
                                foreach($plato->ingredientes->categoria as $cat) {
                                    $nombreCategoria = strtolower((string)$cat);
                                    $iconos[] = $iconosCategorias[$nombreCategoria] ?? htmlspecialchars($cat);
                                }
                                echo '<span class="categories-icons">'.implode(' ', $iconos).'</span>';
                                echo '</div>';
                            }

                            if(!empty((string)$plato->descripcion)) {
                                echo '<p class="item-desc">'.$plato->descripcion.'</p>';
                            }

                            echo '</article>';
                        }
                    }

                    echo '</section>';
                }
            }
            ?>
        </div>
    </main>

    <footer class="menu-footer text-center mt-4 py-4 bg-light border-top">
        <h4 class="mb-3">Categorías</h4>
        <div class="category-legend d-flex justify-content-center flex-wrap gap-3">
            <?php
            foreach ($iconosCategorias as $nombre => $icono) {
                echo '<span class="legend-item">'.$icono.' '.ucfirst($nombre).'</span>';
            }
            ?>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
