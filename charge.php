<?php
  if(!isset($_SESSION)) 
  { 
      session_start(); 
  } 
  require 'navbar.php';

  transactionResult($conn);

  function transactionResult($rconn){
    $conn = $rconn;

    if (isset($_POST['transaction']) && isset($_POST['amount'])) {
        $amount = $_POST['amount'];
        $customerID = $_SESSION['customerID'];
        $query = "UPDATE customers SET credit = credit + '$amount' where customerID = '$customerID'";
		$result = $conn->query($query);
        if (!$result) {
            echo "UPDATE failed: $query<br>" . $conn->error . "<br><br>";
        } else {
            header("Location: ../charge.php?result=success");
            exit();
        }
    }
}

if(isset($_GET['result']) && ($_GET['result']=="success")){
echo " charged successfully!";
} else if(isset($_GET['result']) && ($_GET['result']=="fail")){
    echo " charge failed";
}
?>

  <div class="container-fluid bg-light">
    <div class="container wow fadeIn">

      <!--Grid row-->
      <div class="row">

        <!--Grid column-->
        <div class="col-md-8 mb-4">

          <!--Card-->
          <div class="card">

            <!--Card content-->
            <form class="card-body" action="charge.php" method="post">

              <!--Grid row-->
              <div class="row">

              </div>
              <!--Grid row-->

              <!--Username-->
              <div class="md-form input-group pl-0 mb-5">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">$</span>
                </div>
                <input type="text" class="form-control py-0" name="amount" placeholder="Credit Amount" aria-describedby="basic-addon1">
              </div>

              <!--address-->
              <div class="md-form mb-5">
                <input type="text" id="address" class="form-control" placeholder="1234 Main St">
                <label for="address" class="">Address</label>
              </div>

              <!--address-2-->
              <div class="md-form mb-5">
                <input type="text" id="address-2" class="form-control" placeholder="Apartment or suite">
                <label for="address-2" class="">Address 2 (optional)</label>
              </div>

              <!--Grid row-->
              <div class="row">

                <!--Grid column-->
                <div class="col-lg-4 col-md-12 mb-4">

                  <label for="country">Country</label>
                  <select class="custom-select d-block w-100" id="country" required>
                    <option value="">Choose...</option>
                    <option>United States</option>
                  </select>
                  <div class="invalid-feedback">
                    Please select a valid country.
                  </div>

                </div>
                <!--Grid column-->

                <!--Grid column-->
                <div class="col-lg-4 col-md-6 mb-4">

                  <label for="state">State</label>
                  <select class="custom-select d-block w-100" id="state" required>
                    <option value="">Choose...</option>
                    <option>California</option>
                  </select>
                  <div class="invalid-feedback">
                    Please provide a valid state.
                  </div>

                </div>
                <!--Grid column-->

                <!--Grid column-->
                <div class="col-lg-4 col-md-6 mb-4">

                  <label for="zip">Zip</label>
                  <input type="text" class="form-control" id="zip" placeholder="" required>
                  <div class="invalid-feedback">
                    Zip code required.
                  </div>

                </div>
                <!--Grid column-->

              </div>
              <!--Grid row-->

              <hr>

              <hr>

              <div class="d-block my-3">
                <div class="custom-control custom-radio">
                  <input id="credit" name="paymentMethod" type="radio" class="custom-control-input" checked required>
                  <label class="custom-control-label" for="credit">Credit card</label>
                </div>
                <div class="custom-control custom-radio">
                  <input id="debit" name="paymentMethod" type="radio" class="custom-control-input" required>
                  <label class="custom-control-label" for="debit">Debit card</label>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="cc-name">Name on card</label>
                  <input type="text" class="form-control" id="cc-name" placeholder="" required>
                  <small class="text-muted">Full name as displayed on card</small>
                  <div class="invalid-feedback">
                    Name on card is required
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="cc-number">Credit card number</label>
                  <input type="text" class="form-control" id="cc-number" placeholder="" required>
                  <div class="invalid-feedback">
                    Credit card number is required
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3 mb-3">
                  <label for="cc-expiration">Expiration</label>
                  <input type="text" class="form-control" id="cc-expiration" placeholder="" required>
                  <div class="invalid-feedback">
                    Expiration date required
                  </div>
                </div>
                <div class="col-md-3 mb-3">
                  <label for="cc-expiration">CVV</label>
                  <input type="text" class="form-control" id="cc-cvv" placeholder="" required>
                  <div class="invalid-feedback">
                    Security code required
                  </div>
                </div>
              </div>
              <hr class="mb-4">
              <button class="btn btn-secondary btn-lg btn-block" name="transaction" value="success" type="submit">Charge credit</button>

            </form>

          </div>
          <!--/.Card-->

        </div>
        <!--Grid column-->


        </div>
        <!--Grid column-->

      </div>
      <!--Grid row-->

    </div>
</div>