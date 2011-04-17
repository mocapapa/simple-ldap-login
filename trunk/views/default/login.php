<?php
$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
			 'SimpleLogin',
			 );
?>
<?php if (!$state): ?>
<h1>Login</h1>
<?php else:?>
<h1>Register</h1>
<?php endif; ?>

<div class="form">
<?php echo CHtml::beginForm(); ?>

<?php echo CHtml::errorSummary($form); ?>

<div class="row">
<?php echo CHtml::activeLabel($form,'username'); ?>
<?php echo CHtml::activeTextField($form,'username') ?>
</div>

<div class="row">
<?php echo CHtml::activeLabel($form,'password'); ?>
<?php echo CHtml::activePasswordField($form,'password') ?>
<p class="hint">
You will be registered if you have not been registered yet.
</p>
</div>
<?php if ($state): ?>
<div class="row">
<?php echo CHtml::activeLabel($form,'passwordRepeat'); ?>
<?php echo CHtml::activePasswordField($form,'passwordRepeat') ?>
</div>

<div class="row">
<?php echo CHtml::activeLabel($form,'jn'); ?>
<?php echo CHtml::activeTextField($form,'jn') ?>
<?php echo CHtml::submitButton('Get info') ?>
</div>

<div class="row">
<?php echo CHtml::activeLabel($form,'cn'); ?>
<?php echo CHtml::activeTextField($form,'cn') ?>
</div>

<div class="row">
<?php echo CHtml::activeLabel($form,'email'); ?>
<?php echo CHtml::activeTextField($form,'email') ?>
</div>

<div class="row">
<?php echo CHtml::activeLabel($form,'profile'); ?>
<?php echo CHtml::activeTextField($form,'profile'); ?>
</div>

<div class="row">
<?php echo CHtml::activeLabel($form,'title'); ?>
<?php echo CHtml::activeTextField($form,'title'); ?>
</div>

<div class="row">
<?php echo CHtml::activeLabel($form,'intel'); ?>
<?php echo CHtml::activeTextField($form,'intel'); ?>
</div>
<?php endif; ?>

<div class="action">
<?php echo CHtml::activeCheckBox($form,'rememberMe'); ?>
<?php echo CHtml::activeLabel($form,'rememberMe'); ?>
<br/>
<?php echo CHtml::submitButton('Login'); ?>
</div>

<?php echo CHtml::endForm(); ?>

</div><!-- form -->

<br>
<?php Yii::app()->clientScript->registerCss('dataGrid', '
table.dataGrid
{
	border-collapse: collapse;
}
table.dataGrid th, table.dataGrid td
{
	font-size: 9pt;
	border: 1px #444 solid;
}
table.dataGrid th
{
	background: #81b8d6;
	text-align: center;
}
'); ?>

<?php $i = 0; ?>
<table class="dataGrid">
  <tr>
    <th><?php echo 'Id'; ?></th>
    <th><?php echo 'Account'; ?></th>
    <th><?php echo 'Name'; ?></th>
    <th><?php echo 'E-mail address'; ?></th>
    <th><?php echo 'Profile'; ?></th>
    <th><?php echo 'TEL'; ?></th>
  </tr>
<?php foreach($users as $user): ?>
  <tr class="<?php echo $i++%2?'even':'odd';?>">
    <td><?php echo $user->id ?></td>
    <td><?php echo $user->username ?></td>
    <td><?php echo $user->cn.$user->title ?></td>
    <td><?php echo $user->email ?></td>
    <td><?php echo $user->profile ?></td>
    <td><?php echo $user->intel ?></td>
  </tr>
<?php endforeach; ?>
</table>
