<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\ActiveForm;
use app\assets\AppAsset;

use yii\helpers\BaseUrl;
//https://www.flaticon.com/packs/digital-marketing-63s
//лого можно найти здесь: https://cryptologos.cc

$baseUrl = BaseUrl::base();

//AppAsset::addCss('css/landing.css');

?>

<!-- HERO BLOCK -->
<div style="max-width: 1160px; margin: 0 auto; padding: 56px 20px;">
<div style="display: flex; gap: 64px; align-items: center;"><!-- LEFT: IMAGE AREA -->
<div style="width: 360px; height: 520px; border-radius: 36px; overflow: hidden; background: #ffffff; box-shadow: 0 10px 24px rgba(0,0,0,0.08); display: flex; align-items: center; justify-content: center; padding: 16px; box-sizing: border-box;"><img style="width: 100%; height: 100%; max-width: 100%; max-height: 100%; object-fit: contain; object-position: center; display: block;" src="https://stakenode777.com/images/uploads/tinymce/69555c3ad509f-1_crop.png" alt="Web Solana Node Manager" width="328" height="488" /></div>
<!-- RIGHT: CONTENT -->
<div style="flex: 1; height: 520px; display: flex; flex-direction: column; justify-content: space-between; align-items: center;">
<div style="display: flex; flex-direction: column; align-items: center; padding-top: 0;">
<p style="margin: 0 0 28px 0; text-align: center;"><span style="font-family: 'Roboto-Bold', sans-serif; font-weight: 900; font-size: 34px; line-height: 1.1; color: #333;"> Web Solana Node Manager </span></p>
<p style="margin: 0 0 54px 0; text-align: center;"><span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold; font-size: 22px; line-height: 1.3; color: #333; opacity: 0.9;"> Switch your Solana validator identity easily </span></p>
<!-- FEATURES -->
<div style="display: flex; flex-wrap: wrap; justify-content: center; column-gap: 18px; row-gap: 44px; max-width: 720px;"><!-- Web browser access --> <a style="display: inline-flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 28px; background: linear-gradient(135deg,#0b1022,#0e1a3f,#103a5a,#14f1c9); border: 1px solid rgba(20,241,201,0.35); text-decoration: none; transition: all .25s ease;" href="#block-2"> <span style="font-family: 'Roboto-Bold', sans-serif; font-size: 16px; line-height: 1; color: #fff;"> Web browser access </span> </a> <!-- Mobile control --> <a style="display: inline-flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 28px; background: linear-gradient(135deg,#0b1022,#0e1a3f,#103a5a,#14f1c9); border: 1px solid rgba(20,241,201,0.35); text-decoration: none; transition: all .25s ease;" href="#block-2"> <span style="font-family: 'Roboto-Bold', sans-serif; font-size: 16px; line-height: 1; color: #fff;"> Mobile control </span> </a> <!-- Enhanced security --> <a style="display: inline-flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 28px; background: linear-gradient(135deg,#0b1022,#0e1a3f,#103a5a,#14f1c9); border: 1px solid rgba(20,241,201,0.35); text-decoration: none; transition: all .25s ease;" href="#block-3"> <span style="font-family: 'Roboto-Bold', sans-serif; font-size: 16px; line-height: 1; color: #fff;"> Enhanced security </span> </a> <!-- Simple interface --> <a style="display: inline-flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 28px; background: linear-gradient(135deg,#0b1022,#0e1a3f,#103a5a,#14f1c9); border: 1px solid rgba(20,241,201,0.35); text-decoration: none; transition: all .25s ease;" href="#block-4"> <span style="font-family: 'Roboto-Bold', sans-serif; font-size: 16px; line-height: 1; color: #fff;"> Simple interface </span> </a> <!-- Easy delegation --> <a style="display: inline-flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 28px; background: linear-gradient(135deg,#0b1022,#0e1a3f,#103a5a,#14f1c9); border: 1px solid rgba(20,241,201,0.35); text-decoration: none; transition: all .25s ease;" href="#block-5"> <span style="font-family: 'Roboto-Bold', sans-serif; font-size: 16px; line-height: 1; color: #fff;"> Easy delegation </span> </a> <!-- Free to use --> <a style="display: inline-flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 28px; background: linear-gradient(135deg,#0b1022,#0e1a3f,#103a5a,#14f1c9); border: 1px solid rgba(20,241,201,0.35); text-decoration: none; transition: all .25s ease;" href="#block-6"> <span style="font-family: 'Roboto-Bold', sans-serif; font-size: 16px; line-height: 1; color: #fff;"> Free to use </span> </a> <!-- Open source --> <a style="display: inline-flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 28px; background: linear-gradient(135deg,#0b1022,#0e1a3f,#103a5a,#14f1c9); border: 1px solid rgba(20,241,201,0.35); text-decoration: none; transition: all .25s ease;" href="#block-6"> <span style="font-family: 'Roboto-Bold', sans-serif; font-size: 16px; line-height: 1; color: #fff;"> Open source </span> </a></div>
<!-- BONUS ROW -->
<div style="margin-top: 44px; display: flex; justify-content: center; width: 100%;"><a style="display: inline-flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 28px; background: linear-gradient(135deg,#1a1028,#2a144f,#3a1f7a,#6b5cff); border: 1px solid rgba(107,92,255,0.6); text-decoration: none; box-shadow: 0 8px 22px rgba(107,92,255,0.25);" href="#block-9"> <span style="font-family: 'Roboto-Bold', sans-serif; font-size: 16px; line-height: 1; color: #fff;"> Failover Tool </span> </a></div>
</div>
<!-- TRY IT CTA -->
<div style="display: flex; justify-content: center; width: 100%;"><a style="display: inline-flex; align-items: center; gap: 12px; padding: 16px 26px; border-radius: 32px; background: linear-gradient(315deg,#0b1022,#0e1a3f,#103a5a,#14f1c9); border: 1px solid rgba(20,241,201,0.45); text-decoration: none; box-shadow: 0 10px 26px rgba(20,241,201,0.35);" href="https://wsnm.stakenode777.com/site/register" rel="noopener noreferrer"> <span style="font-family: 'Roboto-Bold', sans-serif; font-size: 17px; line-height: 1; color: #fff;"> Register to explore WSNM </span> </a></div>
</div>
</div>
</div>
<!-- BLOCK 2 -->
<div id="block-2" style="position: relative; left: 50%; right: 50%; margin-left: -50vw; margin-right: -50vw; width: 100vw; overflow: hidden; background: linear-gradient(135deg,#f3f6ff,#e9f1ff,#e5fbf5,#f1fffb); padding: 56px 0;">
<div style="max-width: 1160px; margin: 0 auto; padding: 0 20px; box-sizing: border-box;">
<div style="display: flex; gap: 64px; align-items: center;"><!-- LEFT: TEXT -->
<div style="flex: 1;">
<p style="margin: 0 0 24px 0;"><span style="font-family: 'Roboto-Bold', sans-serif; font-weight: 900; font-size: 34px; line-height: 1.1; color: #333;"> A few clicks. Any place. Any device </span></p>
<p style="margin: 0 0 20px 0;"><span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #333;"> Web Solana Node Manager allows you to transfer your Solana validator identity between servers &mdash; from anywhere in the world </span></p>
<p style="margin: 0 0 26px 0;"><span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #333; opacity: 0.9;"> On the beach, on a train, at the airport, or with friends &mdash; manage your validator without a terminal, workstation, or SSH access </span></p>
<p style="margin: 0;"><span style="font-family: 'Roboto-Bold', sans-serif; font-weight: 900; font-size: 20px; line-height: 1.4; color: #333;"> Just a phone. Just the internet </span></p>
</div>
<!-- RIGHT: IMAGE -->
<div style="width: 360px; height: 520px; border-radius: 36px; overflow: hidden; background: #ffffff; box-shadow: 0 10px 24px rgba(0,0,0,0.08); display: flex; align-items: center; justify-content: center; padding: 16px; box-sizing: border-box; flex-shrink: 0;"><img style="width: 100%; height: 100%; max-width: 100%; max-height: 100%; object-fit: contain; display: block;" src="https://stakenode777.com/images/uploads/tinymce/69555db5a9b86-2.png" alt="WSNM mobile usage" width="328" height="488" /></div>
</div>
</div>
</div>
<!-- BLOCK 3 -->
<div id="block-3" style="position: relative; left: 50%; right: 50%; margin-left: -50vw; margin-right: -50vw; width: 100vw; overflow: hidden; background: #ffffff; padding: 56px 0 50px 0;">
<div style="max-width: 1160px; margin: 0 auto; padding: 0 20px; box-sizing: border-box;">
<div style="display: flex; gap: 64px; align-items: center;"><!-- LEFT: IMAGE -->
<div style="width: 360px; height: 520px; border-radius: 36px; overflow: hidden; background: #ffffff; box-shadow: 0 10px 24px rgba(0,0,0,0.08); display: flex; align-items: center; justify-content: center; padding: 16px; box-sizing: border-box; flex-shrink: 0;"><img style="width: 100%; height: 100%; max-width: 100%; max-height: 100%; object-fit: contain; display: block;" src="https://stakenode777.com/images/uploads/tinymce/6957b9e8c1912-3_1.png" alt="WSNM Security" width="328" height="488" /></div>
<!-- RIGHT: TEXT -->
<div style="flex: 1;">
<p style="margin: 0 0 24px 0;"><span style="font-family: 'Roboto-Bold', sans-serif; font-weight: 900; font-size: 34px; line-height: 1.1; color: #333;"> Security first </span></p>
<p style="margin: 0 0 26px 0;"><span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #333; opacity: 0.9;"> WSNM is built with security as a core principle. It does not store identity private keys or server passwords, and you never need to keep sensitive information on your phone or in the web interface</span></p>
<p style="margin: 0 0 26px 0;"><span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #333; opacity: 0.9;"> All identity keys and server credentials are stored only on the Solana Node Manager (SNM), inside an encrypted directory. WSNM merely sends commands to SNM, while SNM performs all sensitive operations and manages the servers</span></p>
<p style="margin: 0px 0px 26px; text-align: left;"><span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #333; opacity: 0.9;"><span style="font-family: 'Roboto-Bold', sans-serif; font-weight: 900; font-size: 20px; line-height: 1.4; color: #333;">Keys and passwords are safe. No exceptions</span></span></p>
<a style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; color: inherit; cursor: pointer;" href="#block-8"> <img style="width: 28px; height: 28px;" src="https://stakenode777.com/images/uploads/tinymce/695d212dc1e8c-icons8_1.png" alt="рука" /> <span style="font-family: 'Roboto-Bold', sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #333;"> Learn more about SNM and its security model </span> </a></div>
</div>
</div>
</div>
<!-- BLOCK 4 -->
<div id="block-4" style="position: relative; left: 50%; right: 50%; margin-left: -50vw; margin-right: -50vw; width: 100vw; overflow: hidden; background: linear-gradient(135deg,#f3f6ff,#e9f1ff,#e5fbf5,#f1fffb); padding: 56px 0 50px 0;">
<div style="max-width: 1160px; margin: 0 auto; padding: 0 20px; box-sizing: border-box;">
<div style="display: flex; gap: 64px; align-items: center;"><!-- LEFT: TEXT -->
<div style="flex: 1;">
<p style="margin: 0 0 24px 0;"><span style="font-family: 'Roboto-Bold', sans-serif; font-weight: 900; font-size: 34px; line-height: 1.1; color: #333;"> Simple by design </span></p>
<p style="margin: 0 0 20px 0;"><span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #333;"> Web Solana Node Manager turns complex console operations into a clean and intuitive web interface</span></p>
<p style="margin: 0 0 26px 0;"><span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #333; opacity: 0.9;"> Every detail is built for clarity and speed &mdash; especially when time matters most </span></p>
<p style="margin: 0 0 26px 0;"><span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #333; opacity: 0.9;"> You focus on the action. WSNM handles the complexity behind the scenes</span></p>
<p style="margin: 0;"><span style="font-family: 'Roboto-Bold', sans-serif; font-weight: 900; font-size: 20px; line-height: 1.4; color: #333;"> Clear. Fast. Intuitive &mdash; even in critical moments </span></p>
</div>
<!-- RIGHT: IMAGE -->
<div style="width: 360px; height: 520px; border-radius: 36px; overflow: hidden; background: #ffffff; box-shadow: 0 10px 24px rgba(0,0,0,0.08); display: flex; align-items: center; justify-content: center; padding: 16px; box-sizing: border-box; flex-shrink: 0;"><img style="width: 100%; height: 100%; max-width: 100%; max-height: 100%; object-fit: contain; display: block;" src="https://stakenode777.com/images/uploads/tinymce/695acba8c8361-4.png" alt="Simple design" width="328" height="488" /></div>
</div>
</div>
</div>
<!-- BLOCK 5 -->
<div id="block-5" style="position: relative; left: 50%; right: 50%; margin-left: -50vw; margin-right: -50vw; width: 100vw; overflow: hidden; background: #ffffff; padding-top: 50px; padding-bottom: 50px;">
<div style="max-width: 1160px; margin: 0 auto; padding: 0 20px; box-sizing: border-box;">
<div style="display: flex; gap: 64px; align-items: flex-end;"><!-- LEFT: IMAGE -->
<div style="width: 360px; height: 520px; border-radius: 36px; overflow: hidden; background: #ffffff; box-shadow: 0 10px 24px rgba(0,0,0,0.08); display: flex; align-items: center; justify-content: center; padding: 16px; box-sizing: border-box; flex-shrink: 0;"><img style="width: 100%; height: 100%; max-width: 100%; max-height: 100%; object-fit: contain; display: block;" src="https://stakenode777.com/images/uploads/tinymce/6957d549422dc-4_2.png" alt="Delegate Security" width="328" height="488" /></div>
<div style="flex: 1; display: flex; flex-direction: column; height: 520px;">
<div style="flex: 1; display: flex; flex-direction: column; justify-content: center;">
<p style="margin: 0 0 24px 0;"><span style="font-family: 'Roboto-Bold', sans-serif; font-weight: 900; font-size: 34px; line-height: 1.1; color: #333;">Delegate Securely </span></p>
<p style="margin: 0 0 26px 0;"><span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #333; opacity: 0.9;"> WSNM lets you delegate validator management without SSH passwords and without identity private keys</span></p>
<p style="margin: 0 0 26px 0;"><span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #333; opacity: 0.9;"> A trusted admin can transfer the validator between servers while you remain fully in control of security</span></p>
<p style="margin: 0 0 26px 0;"><span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #333; opacity: 0.9;"><span style="font-family: 'Roboto-Bold', sans-serif; font-weight: 900; font-size: 20px; line-height: 1.4; color: #333;">Delegate safely. Stay in control</span></span></p>
</div>
<div style="margin-top: auto; display: flex; justify-content: center; width: 100%;"><a style="display: inline-flex; align-items: center; gap: 12px; padding: 16px 26px; border-radius: 32px; background: linear-gradient(135deg,#0b1022,#0e1a3f,#103a5a,#14f1c9); border: 1px solid rgba(20,241,201,0.45); text-decoration: none; box-shadow: 0 10px 26px rgba(20,241,201,0.35); transition: all .25s ease;" href="https://wsnm.stakenode777.com/site/register" rel="noopener noreferrer"> <span style="font-family: 'Roboto-Bold', sans-serif; font-size: 17px; line-height: 1; color: #fff;"> Register to try WSNM </span> </a></div>
</div>
</div>
</div>
</div>
<!-- BLOCK 6 -->
<div id="block-6" style="position: relative; left: 50%; right: 50%; margin-left: -50vw; margin-right: -50vw; width: 100vw; overflow: hidden; background: linear-gradient(135deg,#f3f6ff,#e9f1ff,#e5fbf5,#f1fffb); padding: 56px 0 50px 0;">
<div style="max-width: 1160px; margin: 0 auto; padding: 0 20px; box-sizing: border-box;">
<div style="display: flex; gap: 64px; align-items: center;"><!-- LEFT: TEXT -->
<div style="flex: 1;">
<p style="margin: 0 0 24px 0;"><span style="font-family: 'Roboto-Bold', sans-serif; font-weight: 900; font-size: 34px; line-height: 1.1; color: #333;"> Free and Open Source </span></p>
<p style="margin: 0 0 20px 0;"><span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #333;"> WSNM is fully open source under the MIT License </span></p>
<p style="margin: 0 0 26px 0;"><span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #333; opacity: 0.9;"> Use our hosted version or deploy it on your own infrastructure &mdash; the choice is yours </span></p>
<p style="margin: 0 0 26px 0;"><span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #333; opacity: 0.9;"> No hidden fees. No vendor lock-in </span></p>
<p style="margin: 0 0 26px 0;"><span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #333; opacity: 0.9;"> You can <a style="font-family: 'Roboto-Bold', sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #000080;" href="https://wsnm.stakenode777.com/site/register" rel="noopener noreferrer"> register</a> or install WSNM yourself <a style="font-family: 'Roboto-Bold', sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #000080;" href="https://github.com/StakeNode777/web-solana-node-manager" target="_blank" rel="noopener noreferrer"> (Github)</a></span></p>
<p style="margin: 0;"><span style="font-family: 'Roboto-Bold', sans-serif; font-weight: 900; font-size: 20px; line-height: 1.4; color: #333;"> Use it for free </span></p>
</div>
<!-- RIGHT: IMAGE -->
<div style="width: 360px; height: 520px; border-radius: 36px; overflow: hidden; background: #ffffff; box-shadow: 0 10px 24px rgba(0,0,0,0.08); display: flex; align-items: center; justify-content: center; padding: 16px; box-sizing: border-box; flex-shrink: 0;"><img style="width: 100%; height: 100%; max-width: 100%; max-height: 100%; object-fit: contain; display: block;" src="https://stakenode777.com/images/uploads/tinymce/695ab8b364667-5(1)_1.png" alt="Free Open Source usage" width="328" height="488" /></div>
</div>
</div>
</div>
<!-- BLOCK 7 -->
<div id="block-7" style="position: relative; left: 50%; right: 50%; margin-left: -50vw; margin-right: -50vw; width: 100vw; overflow: hidden; background: #ffffff; padding-top: 50px; padding-bottom: 50px;">
<div style="max-width: 1160px; margin: 0 auto; padding: 0 20px; box-sizing: border-box;">
<div style="display: flex; gap: 64px; align-items: center;"><!-- LEFT: IMAGE -->
<div style="width: 360px; height: 520px; border-radius: 36px; overflow: hidden; background: #ffffff; box-shadow: 0 10px 24px rgba(0,0,0,0.08); display: flex; align-items: center; justify-content: center; padding: 16px; box-sizing: border-box; flex-shrink: 0;"><img style="width: 100%; height: 100%; max-width: 100%; max-height: 100%; object-fit: contain; display: block;" src="https://stakenode777.com/images/uploads/tinymce/695d09f595cff-7_1.png" alt="Built for Validators" width="328" height="488" /></div>
<div style="flex: 1;">
<p style="margin: 0 0 24px 0;"><span style="font-family: 'Roboto-Bold', sans-serif; font-weight: 900; font-size: 34px; line-height: 1.1; color: #333;">Built by validators, for validators </span></p>
<p style="margin: 0 0 26px 0;"><span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #333; opacity: 0.9;"> WSNM is built by active Solana validators who run nodes and face real operational risks. Downtime, server failures, and urgent migrations are problems we know firsthand</span></p>
<p style="margin: 0 0 26px 0;"><span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #333; opacity: 0.9;"> The tool is designed to let you quickly move validator identity to another server, minimize downtime, and protect your credits without exposing keys or credentials </span></p>
<p style="margin: 0 0 26px 0;"><span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #333; opacity: 0.9;"><span style="font-family: 'Roboto-Bold', sans-serif; font-weight: 900; font-size: 20px; line-height: 1.4; color: #333;">Simple by design. Reliable by default</span></span></p>
</div>
</div>
</div>
</div>
<!-- BLOCK 8 -->
<div id="block-8" style="position: relative; left: 50%; right: 50%; margin-left: -50vw; margin-right: -50vw; width: 100vw; overflow: hidden; background: linear-gradient(135deg,#f3f6ff,#e9f1ff,#e5fbf5,#f1fffb); padding: 56px 0 50px 0;">
<div style="max-width: 1160px; margin: 0 auto; padding: 0 20px; box-sizing: border-box;">
<div style="display: flex; gap: 64px; align-items: center;"><!-- LEFT: TEXT -->
<div style="flex: 1;">
<p style="margin: 0 0 24px 0;"><span style="font-family: 'Roboto-Bold', sans-serif; font-weight: 900; font-size: 34px; line-height: 1.1; color: #333;"> How it works </span></p>
<p style="margin: 0 0 20px 0;"><span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #333;"> Web Solana Node Manager is web UI for Solana Node Manager - simple and secure CLI tool for manual and automated hot-swapping of Solana validator identities between two or more servers </span></p>
<p style="margin: 0 0 26px 0;"><span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #333; opacity: 0.9;"> WSNM merely sends commands to SNM via poor SSH, while SNM performs all sensitive operations and manages the servers </span></p>
<p style="margin: 0 0 26px 0;"><span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #333; opacity: 0.9;"> Sensitive private key and credentials to node servers are stored inside an encrypted directory. When running, Solana Node Manager loads them into memory for the duration of its operation </span></p>
<p style="margin: 0;"><span style="font-family: 'Roboto-Bold', sans-serif; font-weight: 900; font-size: 20px; line-height: 1.4; color: #333;"> Secure by architecture. Simple in use </span></p>
</div>
<!-- RIGHT: IMAGE -->
<div style="width: 360px; height: 520px; border-radius: 36px; overflow: hidden; background: #ffffff; box-shadow: 0 10px 24px rgba(0,0,0,0.08); display: flex; align-items: center; justify-content: center; padding: 16px; box-sizing: border-box; flex-shrink: 0;"><img style="width: 100%; height: 100%; max-width: 100%; max-height: 100%; object-fit: contain; display: block;" src="https://stakenode777.com/images/uploads/tinymce/6960f11434d08-8_1.png" alt="How it works" width="328" height="488" /></div>
</div>
</div>
</div>
<!-- BLOCK 9 -->
<div id="block-9" style="position: relative; left: 50%; right: 50%; margin-left: -50vw; margin-right: -50vw; width: 100vw; overflow: hidden; background: #ffffff; padding-top: 50px; padding-bottom: 50px;">
<div style="max-width: 1160px; margin: 0 auto; padding: 0 20px; box-sizing: border-box;">
<div style="display: flex; gap: 64px; align-items: center;"><!-- LEFT: IMAGE -->
<div style="width: 360px; height: 520px; border-radius: 36px; overflow: hidden; background: #ffffff; box-shadow: 0 10px 24px rgba(0,0,0,0.08); display: flex; align-items: center; justify-content: center; padding: 16px; box-sizing: border-box; flex-shrink: 0;"><img style="width: 100%; height: 100%; max-width: 100%; max-height: 100%; object-fit: contain; display: block;" src="https://stakenode777.com/images/uploads/tinymce/6964f8609816d-9(1).png" alt="Failover Tool" width="328" height="488" /></div>
<div style="flex: 1;">
<p style="margin: 0 0 24px 0;"><span style="font-family: 'Roboto-Bold', sans-serif; font-weight: 900; font-size: 34px; line-height: 1.1; color: #333;">Failover Tool </span></p>
<p style="margin: 0 0 26px 0;"><span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #333; opacity: 0.9;"> Web Solana Node Manager (WSNM) is a web interface for Solana Node Manager (SNM) &mdash; designed to keep your validator online</span></p>
<p style="margin: 0 0 26px 0;"><span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #333; opacity: 0.9;"> SNM is a secure CLI tool for manual and automatic hot-swapping of Solana validator identities between servers, ensuring 99.9%+ uptime</span></p>
<p style="margin: 0 0 26px 0;"><span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #333; opacity: 0.9;"> Automatic failover when the primary server goes down</span></p>
<p style="margin: 0 0 26px 0;"><span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #333; opacity: 0.9;"> No SSH, no panic &mdash; recovery without manual actions</span></p>
<p style="margin: 0 0 26px 0;"><span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #333; opacity: 0.9;"> Instant Telegram notifications for downtime and failovers</span></p>
<p style="margin: 0 0 26px 0;"><span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold; font-size: 18px; line-height: 1.6; color: #333; opacity: 0.9;"><span style="font-family: 'Roboto-Bold', sans-serif; font-weight: 900; font-size: 20px; line-height: 1.4; color: #333;">You stay in control. WSNM handles the critical moments</span></span></p>
</div>
</div>
</div>
</div>
<!-- BLOCK 10 -->
<div id="block-2" style="position: relative; left: 50%; right: 50%; margin-left: -50vw; margin-right: -50vw; width: 100vw; overflow: hidden; background: linear-gradient(135deg,#f3f6ff,#e9f1ff,#e5fbf5,#f1fffb); padding: 56px 0;">
<div style="max-width: 1160px; margin: 0 auto; padding: 0 20px; box-sizing: border-box;">
<div style="display: flex; gap: 64px; align-items: center;">
<div style="flex: 1;">
<p style="margin: 0px 0px 24px; text-align: center;"><span style="font-family: 'Roboto-Bold', sans-serif; font-weight: 900; font-size: 34px; line-height: 1.1; color: #333;"> If your validator goes down, you are ready </span></p>
<div style="margin-top: 44px; display: flex; justify-content: center; width: 100%;"><a style="display: inline-flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 28px; background: linear-gradient(135deg,#0b1022,#0e1a3f,#103a5a,#14f1c9); border: 1px solid rgba(20,241,201,0.35); text-decoration: none; transition: all .25s ease;" href="https://wsnm.stakenode777.com/site/register"  rel="noopener noreferrer"> <span style="font-family: 'Roboto-Bold', sans-serif; font-size: 16px; line-height: 1; color: #fff;"> Register </span> </a></div>
</div>
</div>
</div>
</div>

