</div> <!-- wrap -->
<?php    
    use yii\helpers\BaseUrl;

    $dates = date('Y')==2026 ? 2026 : "2026 - ".date('Y');
    $privacy_policy_url = env('PRIVACY_POLICY_URL');
    $baseUrl = BaseUrl::base();
?>

<footer class="footer navbar-bottom">
    <div class="container text-center">
        © <?=$dates ?>. StakeNode777 — MIT License. <?php if ($privacy_policy_url) : ?>| <a style="display:inline-block!important;" href="<?=$privacy_policy_url?>">Privacy Policy</a><?php endif ?>
    </div>
</footer>

<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>

