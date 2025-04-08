
<?= $this->extend('plantilla/layout')?>
<?= $this->section('contenido') ?>
<table border="2" >
    <thead>
        <th>Titulo</th>
        <th>Descripcion</th>
        <th>Fecha de Vencimiento</th>
    </thead>
    <tbody>
        <td> <?= $titulo ?></td>
        <td> <?= $descripcion ?></td>
        <td> <?= $vencimiento ?></td>
    </tbody>
</table>
<?= $this->endSection() ?>