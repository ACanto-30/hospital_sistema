<!doctype html>
<html lang="es">
<head>
  <?= $this->Html->charset() ?>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title><?= $this->fetch('title') ?: 'Dashboard' ?></title>

  
  <?= $this->Html->css(['app', 'user-dashboard']) ?>
  <?= $this->fetch('css') ?>

  <style>
    .flash-overlay{
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,.55);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      padding: 16px;
    }
    .flash-modal{
      background: #ffffff;
      padding: 26px 28px;
      border-radius: 16px;
      max-width: 460px;
      width: 100%;
      text-align: center;
      box-shadow: 0 20px 40px rgba(0,0,0,.25);
      border: 1px solid #e5e7eb;
    }
    
    .flash-modal .message{
      font-size: 16px;
      margin: 0 0 18px 0;
      line-height: 1.35;
    }
    
    .flash-modal .error,
    .flash-modal .success,
    .flash-modal .warning,
    .flash-modal .info{
      font-size: 16px;
      margin: 0 0 18px 0;
      line-height: 1.35;
    }
    .flash-btn{
      background: #157347;
      color: #fff;
      border: none;
      padding: 10px 18px;
      border-radius: 10px;
      font-weight: 700;
      cursor: pointer;
      min-width: 140px;
    }
    .flash-btn:hover{ opacity:.92; }
  </style>
</head>
<body class="dashboard">

  <?php
    
    $flashHtml = $this->Flash->render();
    if (!empty(trim($flashHtml))):
  ?>
    <div class="flash-overlay" id="flashOverlay">
      <div class="flash-modal">
        <?= $flashHtml ?>
        <button
          class="flash-btn"
          type="button"
          onclick="document.getElementById('flashOverlay').style.display='none'">
          Aceptar
        </button>
      </div>
    </div>
  <?php endif; ?>

  <?= $this->fetch('content') ?>

  <?= $this->fetch('script') ?>
</body>
</html>
