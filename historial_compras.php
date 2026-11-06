<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

require_once 'conexion.php';

$fecha_inicio = trim($_GET['fecha_inicio'] ?? '');
$fecha_fin = trim($_GET['fecha_fin'] ?? '');
$id_proveedor = filter_input(INPUT_GET, 'id_proveedor', FILTER_VALIDATE_INT);
$id_proveedor = ($id_proveedor !== false && $id_proveedor !== null && $id_proveedor > 0)
    ? $id_proveedor
    : 0;

$estado = strtoupper(trim($_GET['estado'] ?? ''));
if (!in_array($estado, ['', 'COMPLETADA', 'ANULADA'], true)) {
    $estado = '';
}

$total_gastado = 0.0;
$conteo_completadas = 0;
$conteo_anuladas = 0;
$filas_compras = [];

$sql = "SELECT
            c.id AS numero_factura,
            c.fecha,
            c.total,
            c.estado,
            p.nombre_empresa AS proveedor,
            u.nombre_completo AS usuario
        FROM compras c
        INNER JOIN proveedores p ON c.proveedor_id = p.id
        INNER JOIN usuarios u ON c.usuario_id = u.id
        WHERE 1=1";

$tipos = '';
$parametros = [];

if ($fecha_inicio !== '') {
    $sql .= " AND DATE(c.fecha) >= ?";
    $tipos .= 's';
    $parametros[] = $fecha_inicio;
}

if ($fecha_fin !== '') {
    $sql .= " AND DATE(c.fecha) <= ?";
    $tipos .= 's';
    $parametros[] = $fecha_fin;
}

if ($id_proveedor > 0) {
    $sql .= " AND c.proveedor_id = ?";
    $tipos .= 'i';
    $parametros[] = $id_proveedor;
}

if ($estado !== '') {
    $sql .= " AND c.estado = ?";
    $tipos .= 's';
    $parametros[] = $estado;
}

$sql .= " ORDER BY c.fecha DESC";

$stmt = $conexion->prepare($sql);
if (!$stmt) {
    die('No se pudo preparar la consulta del historial.');
}

if ($tipos !== '') {
    $stmt->bind_param($tipos, ...$parametros);
}

$stmt->execute();
$resultado = $stmt->get_result();

while ($fila = $resultado->fetch_assoc()) {
    $filas_compras[] = $fila;

    if ($fila['estado'] === 'COMPLETADA') {
        $total_gastado += (float) $fila['total'];
        $conteo_completadas++;
    } elseif ($fila['estado'] === 'ANULADA') {
        $conteo_anuladas++;
    }
}

$stmt_prov = $conexion->prepare(
    'SELECT id, nombre_empresa FROM proveedores ORDER BY nombre_empresa'
);
$stmt_prov->execute();
$res_prov = $stmt_prov->get_result();

function h($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Historial de Compras</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            color: #1f2937;
            margin: 20px;
        }
        .contenedor {
            max-width: 1200px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,.1);
        }
        .encabezado, .acciones {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }
        .encabezado { margin-bottom: 20px; }
        h1 { font-size: 24px; margin: 0; }
        .btn {
            display: inline-block;
            border: 0;
            border-radius: 5px;
            padding: 10px 14px;
            color: #fff;
            text-decoration: none;
            cursor: pointer;
            font-weight: bold;
        }
        .btn-regresar { background: #64748b; }
        .btn-aplicar { background: #2563eb; }
        .btn-limpiar { background: #64748b; }
        .filtros {
            border: 1px solid #e5e7eb;
            border-radius: 7px;
            margin-bottom: 22px;
            overflow: hidden;
        }
        .filtros-titulo { background: #1e293b; color: #fff; padding: 13px 16px; font-weight: bold; }
        .filtros-cuerpo { padding: 16px; }
        .form-grid, .metricas {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }
        label { display: block; margin-bottom: 6px; font-weight: bold; }
        input, select {
            width: 100%;
            padding: 9px;
            border: 1px solid #cbd5e1;
            border-radius: 5px;
            background: #fff;
        }
        .acciones { grid-column: 1 / -1; justify-content: flex-end; margin-top: 4px; }
        .metricas { grid-template-columns: repeat(3, minmax(0, 1fr)); margin-bottom: 22px; }
        .metrica { color: #fff; padding: 18px; border-radius: 7px; }
        .metrica h2 { margin: 8px 0 4px; font-size: 27px; }
        .metrica p { margin: 0; font-size: 13px; }
        .verde { background: #15803d; }
        .azul { background: #2563eb; }
        .rojo { background: #dc2626; }
        .tabla-contenedor { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 11px 10px; border-bottom: 1px solid #cbd5e1; text-align: left; }
        th { background: #1e293b; color: #fff; white-space: nowrap; }
        .monto { color: #059669; font-weight: bold; white-space: nowrap; }
        .estado {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
        }
        .estado-completada { background: #dcfce7; color: #15803d; }
        .estado-anulada { background: #fee2e2; color: #b91c1c; }
        .btn-detalle { background: #2563eb; padding: 6px 10px; font-size: 12px; }
        .sin-registros { text-align: center; color: #64748b; padding: 20px; }
        @media (max-width: 750px) {
            .form-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .metricas { grid-template-columns: 1fr; }
        }
        @media (max-width: 480px) {
            body { margin: 10px; }
            .contenedor { padding: 15px; }
            .form-grid { grid-template-columns: 1fr; }
            .encabezado { align-items: flex-start; flex-direction: column; }
        }
    </style>
</head>
<body>
<div class="contenedor">
    <div class="encabezado">
        <h1>Historial de Compras</h1>
        <a href="dashboard.php" class="btn btn-regresar">Regresar al Panel</a>
    </div>

    <div class="filtros">
        <div class="filtros-titulo">Filtros de Búsqueda Avanzada</div>
        <div class="filtros-cuerpo">
            <form method="GET" action="historial_compras.php" class="form-grid">
                <div>
                    <label for="fecha_inicio">Desde Fecha:</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio"
                           value="<?php echo h($fecha_inicio); ?>">
                </div>

                <div>
                    <label for="fecha_fin">Hasta Fecha:</label>
                    <input type="date" id="fecha_fin" name="fecha_fin"
                           value="<?php echo h($fecha_fin); ?>">
                </div>

                <div>
                    <label for="id_proveedor">Proveedor:</label>
                    <select id="id_proveedor" name="id_proveedor">
                        <option value="0">-- Todos los Proveedores --</option>
                        <?php while ($p = $res_prov->fetch_assoc()): ?>
                            <option value="<?php echo (int) $p['id']; ?>"
                                <?php echo ($id_proveedor == $p['id']) ? 'selected' : ''; ?>>
                                <?php echo h($p['nombre_empresa']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div>
                    <label for="estado">Estado de Factura:</label>
                    <select id="estado" name="estado">
                        <option value="">-- Todos --</option>
                        <option value="COMPLETADA" <?php echo ($estado === 'COMPLETADA') ? 'selected' : ''; ?>>
                            Completada
                        </option>
                        <option value="ANULADA" <?php echo ($estado === 'ANULADA') ? 'selected' : ''; ?>>
                            Anulada
                        </option>
                    </select>
                </div>

                <div class="acciones">
                    <button type="submit" class="btn btn-aplicar">⚡ Aplicar Filtros</button>
                    <a href="historial_compras.php" class="btn btn-limpiar">Limpiar Filtros</a>
                </div>
            </form>
        </div>
    </div>

    <div class="metricas">
        <div class="metrica verde">
            <strong>Monto Total Invertido</strong>
            <h2>$<?php echo number_format($total_gastado, 2); ?></h2>
            <p>Capital neto en compras válidas</p>
        </div>
        <div class="metrica azul">
            <strong>Compras Completadas</strong>
            <h2><?php echo $conteo_completadas; ?></h2>
            <p>Facturas vigentes en inventario</p>
        </div>
        <div class="metrica rojo">
            <strong>Facturas Anuladas</strong>
            <h2><?php echo $conteo_anuladas; ?></h2>
            <p>Operaciones revertidas sin costo</p>
        </div>
    </div>

    <div class="tabla-contenedor">
        <table>
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Fecha y Hora</th>
                    <th>Proveedor</th>
                    <th>Usuario Responsable</th>
                    <th>Total Invertido</th>
                    <th>Estado</th>
                    <th>Detalle</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($filas_compras) > 0): ?>
                    <?php foreach ($filas_compras as $fila): ?>
                        <tr>
                            <td><?php echo h($fila['numero_factura']); ?></td>
                            <td><?php echo h($fila['fecha']); ?></td>
                            <td><?php echo h($fila['proveedor']); ?></td>
                            <td><?php echo h($fila['usuario']); ?></td>
                            <td class="monto">$<?php echo number_format((float) $fila['total'], 2); ?></td>
                            <td>
                                <?php if ($fila['estado'] === 'COMPLETADA'): ?>
                                    <span class="estado estado-completada">ACTIVA</span>
                                <?php else: ?>
                                    <span class="estado estado-anulada">ANULADA</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a class="btn btn-detalle"
                                   href="detalle_compra.php?id=<?php echo urlencode((string) $fila['numero_factura']); ?>">
                                    Ver Detalle
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="sin-registros">Sin registros de compras disponibles.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
<?php
$stmt->close();
$stmt_prov->close();
?