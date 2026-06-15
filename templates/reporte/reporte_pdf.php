<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: Arial, sans-serif; font-size: 12px; color: #1f2937; }
    h1   { font-size: 18px; text-align: center; margin-bottom: 4px; }
    p.subtitulo { text-align: center; font-size: 11px; color: #6b7280; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th    { background: #2563eb; color: #fff; padding: 8px 10px; text-align: left; font-size: 11px; }
    td    { padding: 7px 10px; border-bottom: 1px solid #e5e7eb; font-size: 11px; }
    tr:nth-child(even) td { background: #f9fafb; }
    .badge-proceso    { color: #b45309; font-weight: bold; }
    .badge-completado { color: #15803d; font-weight: bold; }
    .badge-pausado    { color: #b91c1c; font-weight: bold; }
    .pie  { margin-top: 30px; font-size: 10px; color: #9ca3af; text-align: right; }
</style>
</head>
<body>
<h1>TecnoSoluciones S.A.</h1>
<p class="subtitulo">Reporte de Proyectos &mdash; Generado el <?php echo h($fechaReporte); ?></p>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Proyecto</th>
            <th>Cliente</th>
            <th>RUC</th>
            <th>Estado</th>
            <th>Inicio</th>
            <th>Fin</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($proyectos as $p): ?>
            <?php
            $estadoClase = "badge-proceso";
            if ($p["estado"] === "Completado") $estadoClase = "badge-completado";
            if ($p["estado"] === "Pausado")    $estadoClase = "badge-pausado";
            $fechaFin = $p["fecha_fin"] ? h($p["fecha_fin"]) : "—";
            ?>
            <tr>
                <td><?php echo h($p["id"]); ?></td>
                <td><?php echo h($p["nombre"]); ?></td>
                <td><?php echo h($p["cliente_nombre"]); ?></td>
                <td><?php echo h($p["ruc"]); ?></td>
                <td class="<?php echo $estadoClase; ?>"><?php echo h($p["estado"]); ?></td>
                <td><?php echo h($p["fecha_inicio"]); ?></td>
                <td><?php echo $fechaFin; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<p class="pie">Total de proyectos: <?php echo count($proyectos); ?></p>
</body>
</html>
