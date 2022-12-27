<form id="payment" class="form-horizontal">
  <fieldset>
    <legend><?php echo $text_credit_card; ?></legend>
	<?php if (isset($text_testing)) { ?>
		<div class="alert alert-warning"><?php echo $text_testing; ?></div>
	<?php } ?>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-cc-number"><?php echo $entry_cc_number; ?></label>
      <div class="col-sm-6">
       <div id="card-number" class="field card-number" style="height: 3.4rem; overflow: hidden;"></div>
       <div class="input-errors" id="card-number-errors" role="alert"></div>
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-cc-expire-date"><?php echo $entry_cc_expire_date; ?></label>
      <div class="col-sm-3"  >
        <div id="card-date" class="field third-width" style="height: 3.4rem; overflow: hidden;"></div>
        <div class="input-errors" id="card-date-errors" role="alert"></div>
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-cc-cvv"><?php echo $entry_cc_cvv; ?></label>
      <div class="col-sm-3">
        <div id="card-cvv" class="field third-width" style="height: 3.4rem; overflow: hidden;"></div>
        <div class="input-errors" id="card-cvv-errors" role="alert"></div>
        </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-cc-cvv"><?php echo $entry_cc_postal; ?></label>
      <div class="col-sm-3">
          <div id="card-postal-code" class="field third-width" style="height: 3.4rem; overflow: hidden;"></div>
          <div class="input-errors" id="card-postal-code-errors" role="alert"></div>
      </div>
    </div>
    
  <div id="card-response" role="alert"></div>
  </fieldset>
</form>
<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" />
  </div>
</div>

<script type="text/javascript">


(async () => {
    
function loadScript(src) {
return new Promise((resolve) => {
  const script = document.createElement('script');
  script.src = src;
  script.onload = () => {
    resolve(true);
  };
  script.onerror = () => {
    resolve(false);
  };
  document.body.appendChild(script);
});
}

const res = await Promise.all([loadScript("<?php echo $polyfill; ?>"), loadScript("<?php echo $clover_sdk; ?>")]);

if(!res[0] || !res[1]){
    alert('Clover SDK failed to load. please check are you online?');
    return;
}

const clover = new Clover("<?php echo $clover_pub_key; ?>");
const elements = clover.elements();

const form = document.getElementById('payment');

const inputStyle = {
    'display': 'block',
    'width': '100%',
    'height': '34px',
    'padding': '6px 12px',
    'font-size': '14px',
    'line-height': '1.42857143',
    'color': '#555',
    'background-color': '#fff',
    'background-image': 'none',
    'border': '1px solid #ccc',
    'border-radius': '4px',
    '-webkit-box-shadow': 'inset 0 1px 1px rgb(0 0 0 / 8%)',
    'box-shadow': 'inset 0 1px 1px rgb(0 0 0 / 8%)',
    '-webkit-transition': 'border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s',
    '-o-transition': 'border-color ease-in-out .15s,box-shadow ease-in-out .15s',
    'transition': 'border-color ease-in-out .15s,box-shadow ease-in-out .15s'
}

const inputFucusStyle = {
    'box-shadow': 'none',
    'outline': 'none',
    'border-color': '#66afe9',
}


const styles = {
  'card-number input': inputStyle,
  'card-number input:focus': inputFucusStyle,
  'card-number .brand' : {
        'right': '2px',
        'top': '50%',
        'transform': 'translateY(-50%)'
  },
  'card-date input': inputStyle,
  'card-date input:focus': inputFucusStyle,
  'card-cvv input': inputStyle,
  'card-cvv input:focus': inputFucusStyle,
  'card-postal-code input': inputStyle,
  'card-postal-code input:focus': inputFucusStyle
};

const cardNumber = elements.create('CARD_NUMBER', styles);
const cardDate = elements.create('CARD_DATE', styles);
const cardCvv = elements.create('CARD_CVV', styles);
const cardPostalCode = elements.create('CARD_POSTAL_CODE', styles);
  
cardNumber.mount('#card-number');
cardDate.mount('#card-date');
cardCvv.mount('#card-cvv');
cardPostalCode.mount('#card-postal-code');

const cardResponse = document.getElementById('card-response');
const displayCardNumberError = document.getElementById('card-number-errors');
const displayCardDateError = document.getElementById('card-date-errors');
const displayCardCvvError = document.getElementById('card-cvv-errors');
const displayCardPostalCodeError = document.getElementById('card-postal-code-errors');
const submit = document.getElementById('button-confirm');

let validatingCardNumber = false;
let validatingCardDate = false;
let validatingCardCvv = false;
let validatingCardPostalCode = false;

function addErrorMsg(element, obj, type){
    
    if(obj.error){
        element.textContent = obj.error;
        element.className = "text-danger";
    }else{
        element.textContent = "";
        element.className = "";
    }
}

  cardNumber.addEventListener('blur', function(event) {
  // Handle real-time validation errors from the card element
      if(!validatingCardNumber){
          addErrorMsg(displayCardNumberError, event.CARD_NUMBER, 'text-danger');
          cardNumber.addEventListener('change', function(event) {
              addErrorMsg(displayCardNumberError, event.CARD_NUMBER, 'text-danger');
          });
          validatingCardNumber = true;
      }
  });
  
cardDate.addEventListener('blur', function(event) {
    
  // Handle real-time validation errors from the card element
      if(!validatingCardDate){
          addErrorMsg(displayCardDateError, event.CARD_DATE, 'text-danger');
          cardDate.addEventListener('change', function(event) {
              addErrorMsg(displayCardDateError, event.CARD_DATE, 'text-danger');
          });
          validatingCardDate = true;
      }
  });
  
  cardCvv.addEventListener('blur', function(event) {
  // Handle real-time validation errors from the card element
      if(!validatingCardCvv){
          addErrorMsg(displayCardCvvError, event.CARD_CVV, 'text-danger');
          cardCvv.addEventListener('change', function(event) {
              addErrorMsg(displayCardCvvError, event.CARD_CVV, 'text-danger');
          });
          validatingCardCvv = true;
      }
  });
  
  cardPostalCode.addEventListener('blur', function(event) {
  // Handle real-time validation errors from the card element
      if(!validatingCardPostalCode){
          addErrorMsg(displayCardPostalCodeError, event.CARD_POSTAL_CODE, 'text-danger');
          cardPostalCode.addEventListener('change', function(event) {
              addErrorMsg(displayCardPostalCodeError, event.CARD_POSTAL_CODE, 'text-danger');
          });
          validatingCardPostalCode = true;
      }
  });


function cloverTokenHandler(token) {
  // Insert the token ID into the form so it gets submitted to the server
  var form = document.getElementById('payment');
  var hiddenInput = document.createElement('input');
  hiddenInput.setAttribute('type', 'hidden');
  hiddenInput.setAttribute('name', 'cloverToken');
  hiddenInput.setAttribute('value', token);
  form.appendChild(hiddenInput);
//   form.submit();
   	$.ajax({
		url: 'index.php?route=payment/clover/send',
		type: 'post',
		data: $('#payment :input'),
		dataType: 'json',
		cache: false,
		beforeSend: function() {
			$('#button-confirm').button('loading');
		},
		complete: function() {
			$('#button-confirm').button('reset');
		},
		success: function(json) {
			if (json['error']) {
				cardResponse.className = "alert alert-danger";
                cardResponse.innerHTML = json['error'];
				
			}

			if (json['redirect']) {
			    cardResponse.className = "alert alert-success";
                cardResponse.textContent = "Order placed successfully.";
				location = json['redirect'];
			}
		}
	});
}


submit.addEventListener('click', function(event) {
  event.preventDefault();
  
  cardResponse.className = "alert alert-info";
  cardResponse.textContent = "Processing...";
  
  // Use the iframe's tokenization method with the user-entered card details
  clover.createToken()
    .then(function(result) {
    if (result.errors) {
      Object.values(result.errors).forEach(function (value) {
        displayError.textContent = value;
      });
    } else {
      cloverTokenHandler(result.token);
    }
  });
});
})()
</script>