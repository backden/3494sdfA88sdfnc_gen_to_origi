<!DOCTYPE html>
<!-- To change this license header, choose License Headers in Project Properties. To change this template file, choose Tools | Templates and open the template in the editor. -->
<html>

    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>

    <body>
        <div>
            <label >
                <input type="radio"/>
            </label>
        </div>
        <div>
            <p ><?= __d($domain, "lbl001"); ?></p>
        </div>
        <div>
            <a><?= __d($domain, "lbl003"); ?></a>
        </div>
        <div>
            <?= $this->Form->select("ddl001", [], ['label' => false, 'class' => 'form-control']); ?>
            <?= $this->Form->button("ddl001", __d($domain, "btn001"), ['label' => false, 'class' => 'form-control']); ?>
        </div>
        <div>
            <?= $this->Form->input('txt001', ['label' => false, 'class' => 'form-control']); ?></div>
        <div>
            <?= $this->Form->label("lbl002", __d($domain, "lbl002")); ?>
            <?= $this->Form->input('txt002', ['label' => false, 'class' => 'form-control']); ?>
            <?= $this->Form->button("ddl002", __d($domain, "btn002"), ['label' => false, 'class' => 'form-control']); ?>
        </div>
        <div>
            <?= $this->Form->label("lbl005", __d($domain, "lbl005")); ?>
        </div>
    </body>

</html>