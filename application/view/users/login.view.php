    <div class="bsq">
      <h2 class="bsqh">Logga in</h2>
      <div class="bsqp">
        <form class="standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
          <div class="input">
            <label for="uname">Anv&auml;ndarnamn:</label>
            <input name="data[user]" type="text" id="uname" />
          </div>
          <div class="input">
            <label for="pw">L&ouml;senord:</label>
            <input name="data[pw]" type="password" id="pw" />
          </div>
          <input name="submit" type="submit" value="logga in" />
        </form>
      </div>
    </div>