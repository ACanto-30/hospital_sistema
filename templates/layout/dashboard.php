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
  <style>

#toast-container{
  position: fixed;
  top: 20px;
  right: 20px;
  z-index: 9998; 
}

.toast{
  min-width: 260px;
  margin-bottom: 12px;
  padding: 14px 18px;
  border-radius: 10px;
  color: #fff;
  font-weight: 600;
  box-shadow: 0 10px 25px rgba(0,0,0,.25);
  animation: slideIn .4s ease, fadeOut .5s ease 3.5s forwards;
}

.toast-success{
  background: linear-gradient(135deg,#16a34a,#22c55e);
}

.toast-error{
  background: linear-gradient(135deg,#dc2626,#ef4444);
}

@keyframes slideIn{
  from{transform:translateX(120%); opacity:0;}
  to{transform:translateX(0); opacity:1;}
}

@keyframes fadeOut{
  to{opacity:0; transform:translateX(120%);}
}
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

  div id="toast-container">
  <?= $this->Flash->render('toast') ?>
</div>

  <?= $this->fetch('content') ?>

  <?= $this->fetch('script') ?>
</body>
</html>
