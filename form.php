<?php 
<form action="<?php echo admin_url('admin-ajax.php?action=send_mail'); ?>" method="post" class="form-send-mail">
    <div class="form-group">
        <input type="text" placeholder="Имя*">
    </div>
    <div class="form-group">
        <input type="tel" placeholder="+7 (___) ___-__-__">
    </div>
    <div class="form-group">
        <input type="email" placeholder="E-mail*">
    </div>
    <div class="form-group">
        <input type="submit" class="btn-main" value="Получить каталог">
    </div>
</form>

?>