<head>
    <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">
    <style>


.container403{
  position: absolute;
  right: 30px;
}
.message{
  font-size: 30px;
  color: white;
  font-weight: 500;
  position: absolute;
  top: 230px;
  left: 40px;
}
.message2{
  font-family: 'Poppins', sans-serif;
  font-size: 18px;
  color: white;
  font-weight: 300;
  width: 360px;
  position: absolute;
  top: 280px;
  left: 40px;
}

.neon{
  text-align: center;
  width: 300px;
  margin-top: 30px;
  margin-bottom: 10px;
  font-family: 'Varela Round', sans-serif;
  font-size: 90px;
  color: var(--color1);
  letter-spacing: 3px;
  text-shadow: 0 0 5px  var(--color1);
  
}
.trash{
  width: 170px;
  height: 220px;
  background-color: #585F67;
  top: 300px;
}
.can{
  width: 190px;
  height: 30px;
  background-color: #6B737C;
  border-radius: 15px 15px 0 0;
}
.door-frame {
  height: 495px;
  width: 295px;
  border-radius: 90px 90px 0 0;
  background-color: #8594A5;
  display: flex;
  justify-content: center;
  align-items: center;
}

.door{
  height: 450px;
  width: 250px;
  border-radius: 70px 70px 0 0;
  background-color: #A0AEC0;
}

.eye{
  top: 15px;
  left: 25px;
  height: 5px;
  width: 15px;
  border-radius: 50%;
  background-color: white;
  position: absolute;
 }
.eye2{
  left: 65px;
}

.window{
  height: 40px;
  width: 130px;
  background-color: #1C2127;
  border-radius: 3px;
  margin: 80px auto;
  position: relative;
}

.leaf{
  height: 40px;
  width: 130px;
  background-color: #8594A5;
  border-radius: 3px;
  transform-origin: right;
}

.handle {
  height: 8px;
  width: 50px;
  border-radius: 4px;
  background-color: #EBF3FC;
  position: absolute;
  margin-top: 250px;
  margin-left: 30px;
}

.rectangle {
  height: 70px;
  width: 25px;
  background-color: #CBD8E6;
  border-radius: 4px;
  position: absolute;
  margin-top: 220px;
  margin-left: 20px;
}

@keyframes leaf {
  0% {
    transform: scaleX(1);
  }
  5% {
    transform: scaleX(0.2);
  } 
  70%{
    transform: scaleX(0.2);
  }
  75%{
    transform: scaleX(1);
  }
  100% {
    transform: scaleX(1);
  }
}

@keyframes eye {
  0% {
    opacity: 0;
    transform: translateX(0)
  }
  5% {
    opacity: 0;
  }
  15%{
    opacity: 1;
    transform: translateX(0)
  }
  20% {
    transform: translateX(15px)
  }
  35%{
    transform: translateX(15px)
  }
  40%{
    transform: translateX(-15px)
  }
  60%{
    transform: translateX(-15px)
  }
  65% {
    transform: translateX(0)
  }
}

@keyframes flux {
  0%,
  100% {
    text-shadow: 0 0 5px #5b24ff, 0 0 15px #5b24ff, 0 0 50px #5b24ff, 0 0 50px #5b24ff, 0 0 2px #d9ccff, 2px 2px 3px #5b24ff;
    color: #9673ff;
  }
  50% {
    text-shadow: 0 0 3px #4d29ba, 0 0 7px #4d29ba, 0 0 25px #4d29ba, 0 0 25px #4d29ba, 0 0 2px #4d29ba, 2px 2px 3px #664ead;
    color: #664ead;
  }
}

  

</style>
</head>

  <div class="message">You are not authorized.
  </div>
  <div class="message2">You tried to access a page you did not have prior authorization for.</div>
  <div class="container403">
    <div class="neon">403</div>
    <div class="door-frame">
      <div class="door">
        <div class="rectangle">
      </div>
        <div class="handle">
          </div>
        <div class="window">
          <div class="eye">
          </div>
          <div class="eye eye2">
          </div>
          <div class="leaf">
          </div> 
        </div>
      </div>  
    </div>
  </div>