        <form class="form_standard" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
          <div class="div_input">
            <label for="input_rolename"><span>Namn p&aring; roll:</span></label>
            <input name="data[name]" type="text" id="input_rolename" />
          </div>
          <input value="spara roll" type="submit" class="input_submit" />
        </form>