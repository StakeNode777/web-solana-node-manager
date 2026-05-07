<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\ActiveForm;
use app\assets\AppAsset;

use yii\helpers\BaseUrl;

$baseUrl = BaseUrl::base();

AppAsset::addCss('css/landing.css');

// Register Font Awesome
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', ['position' => \yii\web\View::POS_HEAD]);
?>

<div class="wrap">

<div class="container-fluid landing-info">

    <!-- 1 -->
    <div id="block-1" class="row row-odd align-items-center header" id="lm-keyword-rank">
        <div class="col-12 col-md-5 offset-md-2 img-block revealator-slideright revealator-once revealator-delay3">
            <img class="img-fluid" src="<?=$baseUrl?>/images/landing/header.png" alt="WSNM">
        </div>
        <div style="justify-content: center; text-align: center;" class="col-12 col-md-5 description revealator-slideleft revealator-once revealator-delay3">
            <h2>Web Solana Node Manager</h2>
            <h3>Switch your Solana validator identity easily</h3>

            <!-- FEATURES -->
            <div style="display: flex; flex-wrap: wrap; justify-content: center; column-gap: 18px; row-gap: 44px; max-width: 720px;"><!-- Web browser access --> <a style="display: inline-flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 28px; background: linear-gradient(135deg,#0b1022,#0e1a3f,#103a5a,#14f1c9); border: 1px solid rgba(20,241,201,0.35); text-decoration: none; transition: all .25s ease;" href="#block-2"> <span style="font-family: 'Roboto-Bold', sans-serif; font-size: 16px; line-height: 1; color: #fff;"> Web browser access </span> </a> <!-- Mobile control --> <a style="display: inline-flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 28px; background: linear-gradient(135deg,#0b1022,#0e1a3f,#103a5a,#14f1c9); border: 1px solid rgba(20,241,201,0.35); text-decoration: none; transition: all .25s ease;" href="#block-2"> <span style="font-family: 'Roboto-Bold', sans-serif; font-size: 16px; line-height: 1; color: #fff;"> Mobile control </span> </a> <!-- Enhanced security --> <a style="display: inline-flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 28px; background: linear-gradient(135deg,#0b1022,#0e1a3f,#103a5a,#14f1c9); border: 1px solid rgba(20,241,201,0.35); text-decoration: none; transition: all .25s ease;" href="#block-3"> <span style="font-family: 'Roboto-Bold', sans-serif; font-size: 16px; line-height: 1; color: #fff;"> Enhanced security </span> </a> <!-- Simple interface --> <a style="display: inline-flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 28px; background: linear-gradient(135deg,#0b1022,#0e1a3f,#103a5a,#14f1c9); border: 1px solid rgba(20,241,201,0.35); text-decoration: none; transition: all .25s ease;" href="#block-4"> <span style="font-family: 'Roboto-Bold', sans-serif; font-size: 16px; line-height: 1; color: #fff;"> Simple interface </span> </a> <!-- Easy delegation --> <a style="display: inline-flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 28px; background: linear-gradient(135deg,#0b1022,#0e1a3f,#103a5a,#14f1c9); border: 1px solid rgba(20,241,201,0.35); text-decoration: none; transition: all .25s ease;" href="#block-5"> <span style="font-family: 'Roboto-Bold', sans-serif; font-size: 16px; line-height: 1; color: #fff;"> Easy delegation </span> </a> <!-- Free to use --> <a style="display: inline-flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 28px; background: linear-gradient(135deg,#0b1022,#0e1a3f,#103a5a,#14f1c9); border: 1px solid rgba(20,241,201,0.35); text-decoration: none; transition: all .25s ease;" href="#block-6"> <span style="font-family: 'Roboto-Bold', sans-serif; font-size: 16px; line-height: 1; color: #fff;"> Free to use </span> </a> <!-- Open source --> <a style="display: inline-flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 28px; background: linear-gradient(135deg,#0b1022,#0e1a3f,#103a5a,#14f1c9); border: 1px solid rgba(20,241,201,0.35); text-decoration: none; transition: all .25s ease;" href="#block-6"> <span style="font-family: 'Roboto-Bold', sans-serif; font-size: 16px; line-height: 1; color: #fff;"> Open source </span> </a></div>
            <!-- BONUS ROW -->
            <div style="margin-top: 44px; display: flex; justify-content: center; width: 100%;"><a style="display: inline-flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 28px; background: linear-gradient(135deg,#1a1028,#2a144f,#3a1f7a,#6b5cff); border: 1px solid rgba(107,92,255,0.6); text-decoration: none; box-shadow: 0 8px 22px rgba(107,92,255,0.25);" href="#block-9"> <span style="font-family: 'Roboto-Bold', sans-serif; font-size: 16px; line-height: 1; color: #fff;"> Failover Tool </span> </a></div>
            <!-- TRY IT CTA -->
            <div style="display: flex; justify-content: center; width: 100%; margin-top: 3rem;"><a style="display: inline-flex; align-items: center; gap: 12px; padding: 16px 26px; border-radius: 32px; background: linear-gradient(315deg,#0b1022,#0e1a3f,#103a5a,#14f1c9); border: 1px solid rgba(20,241,201,0.45); text-decoration: none; box-shadow: 0 10px 26px rgba(20,241,201,0.35);" href="<?=$baseUrl?>/site/register" rel="noopener noreferrer"> <span style="font-family: 'Roboto-Bold', sans-serif; font-size: 17px; line-height: 1; color: #fff;"> Register to explore WSNM </span> </a></div>
        </div>
    </div>

    <!-- 2 -->
    <div id="block-2" class="row align-items-center" id="lm-sales-dashboard">
        <div class="col-12 col-md-5 order-md-2 img-block revealator-slideleft revealator-once revealator-delay3">
            <img class="img-fluid" src="<?=$baseUrl?>/images/landing/few_clicks.png" alt="A few clicks. Any place. Any device">
        </div>
        <div class="col-12 col-md-5 offset-md-2 order-md-1 description revealator-slideright revealator-once revealator-delay3">
            <h2>A few clicks. Any place. Any device</h2>
            <p><strong>Web Solana Node Manager allows you to transfer your Solana validator identity between servers — from anywhere in the world</strong></p>
            <p>On the beach, on a train, at the airport, or with friends — manage your validator without a terminal, workstation, or SSH access</p>
            <p><strong>Just a phone. Just the internet</strong></p>
        </div>
    </div>

    <!-- 3 -->
    <div id="block-3" class="row row-odd align-items-center" id="lm-reviews-tracker">
        <div class="col-12 col-md-5 offset-md-2 img-block revealator-slideright revealator-once revealator-delay3">
            <img class="img-fluid" src="<?=$baseUrl?>/images/landing/security_first.png" alt="Security first">
        </div>
        <div class="col-12 col-md-5 description revealator-slideleft revealator-once revealator-delay3">
            <h2>Security first</h2>
            <p>WSNM is built with security as a core principle. It does not store identity private keys or server passwords, and you never need to keep sensitive information on your phone or in the web interface</p>
            <p>All identity keys and server credentials are stored only on the Solana Node Manager (SNM), inside an encrypted directory. WSNM merely sends commands to SNM, while SNM performs all sensitive operations and manages the servers</p>
            <p>Keys and passwords are safe. No exceptions.</p>
            <a style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; color: inherit; cursor: pointer;" href="#block-8"> <img style="width: 28px; height: 28px;" src="https://stakenode777.com/images/uploads/tinymce/695d212dc1e8c-icons8_1.png" alt="рука" /> <span style="font-family: 'Roboto-Bold', sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #333;"> Learn more about SNM and its security model </span> </a>
        </div>
    </div>

    <!-- 4 -->
    <div id="block-4" class="row align-items-center" id="lm-feedback-tracker">
        <div class="col-12 col-md-5 order-md-2 img-block revealator-slideleft revealator-once revealator-delay3">
            <img class="img-fluid" src="<?=$baseUrl?>/images/landing/simple_by_design.png" alt="Simple by design">
        </div>
        <div class="col-12 col-md-5 offset-md-2 order-md-1 description revealator-slideright revealator-once revealator-delay3">
            <h2>Simple by design</h2>
            <p>Web Solana Node Manager turns complex console operations into a clean and intuitive web interface.</p>
            <p>Every detail is built for clarity and speed — especially when time matters most</p>
            <p>You focus on the action. WSNM handles the complexity behind the scenes</p>
            <p><strong>Clear. Fast. Intuitive — even in critical moments</strong></p>
        </div>
    </div>

    <!-- 5 -->
    <div id="block-5" class="row row-odd align-items-center" id="lm-multiple-accounts">
        <div class="col-12 col-md-5 offset-md-2 img-block revealator-slideright revealator-once revealator-delay3">
            <img class="img-fluid" src="<?=$baseUrl?>/images/landing/delegate_securely.png" alt="Delegate Securely">
        </div>
        <div class="col-12 col-md-5 description revealator-slideleft revealator-once revealator-delay3">
            <h2>Delegate Securely</h2>
            <p>WSNM lets you delegate validator management without SSH passwords and without identity private keys</p>
            <p>A trusted admin can transfer the validator between servers while you remain fully in control of security</p>
            <p><strong>Delegate safely. Stay in control</strong></p>
        </div>
    </div>

    <!-- 6 -->
    <div id="block-6" class="row align-items-center" id="lm-notifications">
        <div class="col-12 col-md-5 order-md-2 img-block revealator-slideleft revealator-once revealator-delay3">
            <img class="img-fluid" src="<?=$baseUrl?>/images/landing/free_and_opensource.png" alt="Free and Open Source">
        </div>
        <div class="col-12 col-md-5 offset-md-2 order-md-1 description revealator-slideright revealator-once revealator-delay3">
            <h2>Free and Open Source</h2>
            <p><strong>WSNM is fully open source under the MIT License.</strong></p>
            <p>Use our hosted version or deploy it on your own infrastructure — the choice is yours</p>
            <p>No hidden fees. No vendor lock-in.</p>

            <p>
                You can  
                <a style="font-family: 'Roboto-Bold', sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #000080;" href="https://wsnm.stakenode777.com/site/register" rel="noopener noreferrer"> register</a>
                or install WSNM yourself 
                <a style="font-family: 'Roboto-Bold', sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #000080;" href="https://github.com/StakeNode777/web-solana-node-manager" target="_blank" rel="noopener noreferrer"> (Github)</a>
            </p>
            <p><strong>Use it for free</strong></p>
        </div>
    </div>

    <!-- 7 -->
    <div id="block-7" class="row row-odd align-items-center">
        <div class="col-12 col-md-5 offset-md-2 img-block revealator-slideright revealator-once revealator-delay3">
            <img class="img-fluid" src="<?=$baseUrl?>/images/landing/built_by.png" alt="Built by validators, for validators">
        </div>
        <div class="col-12 col-md-5 description revealator-slideleft revealator-once revealator-delay3">
            <h2>Built by validators, for validators</h2>
            <p>WSNM is built by active Solana validators who run nodes and face real operational risks. Downtime, server failures, and urgent migrations are problems we know firsthand</p>
            <p>The tool is designed to let you quickly move validator identity to another server, minimize downtime, and protect your credits without exposing keys or credentials</p>
            <p><strong>Simple by design. Reliable by default.</strong></p>
        </div>
    </div>

    <!-- 8 -->
    <div id="block-8" class="row align-items-center">
        <div class="col-12 col-md-5 order-md-2 img-block revealator-slideleft revealator-once revealator-delay3">
            <img class="img-fluid" src="<?=$baseUrl?>/images/landing/how_it_works.png" alt="How it works">
        </div>
        <div class="col-12 col-md-5 offset-md-2 order-md-1 description revealator-slideright revealator-once revealator-delay3">
            <h2>How it works</h2>
            <p>Web Solana Node Manager is web UI for Solana Node Manager - simple and secure CLI tool for manual and automated hot-swapping of Solana validator identities between two or more servers</p>
            <p>WSNM merely sends commands to SNM via poor SSH, while SNM performs all sensitive operations and manages the servers</p>
            <p>Sensitive private key and credentials to node servers are stored inside an encrypted directory. When running, Solana Node Manager loads them into memory for the duration of its operation</p>
            <p><strong>Secure by architecture. Simple in use.</strong></p>
        </div>
    </div>

    <!-- 9 -->
    <div id="block-9" class="row row-odd align-items-center">
        <div class="col-12 col-md-5 offset-md-2 img-block revealator-slideright revealator-once revealator-delay3">
            <img class="img-fluid" src="<?=$baseUrl?>/images/landing/failover.png" alt="Failover tool">
        </div>
        <div class="col-12 col-md-5 description revealator-slideleft revealator-once revealator-delay3">
            <h2>Failover Tool</h2>
            <p>Web Solana Node Manager (WSNM) is a web interface for Solana Node Manager (SNM) — designed to keep your validator online</p>
            <p>SNM is a secure CLI tool for manual and automatic hot-swapping of Solana validator identities between servers, ensuring 99.9%+ uptime</p>
            <p>Automatic failover when the primary server goes down</p>
            <p>No SSH. No panic. Instant recovery.</p>
            <p>Instant Telegram notifications for downtime and failovers</p>
            <p><strong>You stay in control. WSNM handles the critical moments.</strong></p>
        </div>
    </div>

     <!-- 10 -->
    <div style="justify-content: center; text-align: center;" class="row align-items-center">
        <h2>
            If your validator goes down, you are ready
        </h2>

        <div style="margin-top: 44px; display: flex; justify-content: center; width: 100%;"><a style="display: inline-flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 28px; background: linear-gradient(135deg,#0b1022,#0e1a3f,#103a5a,#14f1c9); border: 1px solid rgba(20,241,201,0.35); text-decoration: none; transition: all .25s ease;" href="<?=$baseUrl?>/site/register"  rel="noopener noreferrer"> <span style="font-family: 'Roboto-Bold', sans-serif; font-size: 16px; line-height: 1; color: #fff;"> Register </span> </a></div>
    </div>

</div>

</div> <!-- wrap -->