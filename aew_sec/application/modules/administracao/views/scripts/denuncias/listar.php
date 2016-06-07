<a href="/administracao/denuncias/historico">Histórico</a>
<table class="table table-hover table-condensed">
    <thead>
        <tr>
            <th>Denuncia</th>
            <th>Data de Criação</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($this->denuncias as $denuncia): ?>
            <tr>
                <td>
                <?php echo $denuncia->getTitulo(); ?>
                </td>
                <td><?php echo $this->datetime($denuncia->getDatacriacao()) ?></td>
                <td>
                    <a href="/administracao/denuncia/exibir/id/<?php echo $denuncia->getId(); ?>"> Ver</a>
                </td>
            </tr> 
        <?php endforeach; ?>
    </tbody>
</table>

