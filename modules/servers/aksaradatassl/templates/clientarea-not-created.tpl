{if $error_message}
<div class="alert alert-danger">
  {$error_message}
</div>
{/if}
<div class="tab-content margin-bottom">
    <div class="tab-panel fade in active" id="tabOverview">
            <div class="product-details clearfix">
                <div class="row">
                    <div class="col-md-6">
                        <div class="product-status">
                            <div class="productdetailicon">
                                <img src="https://portal.qwords.com/templates/qwordsv2/img/list manage produk/globe.png">
                            </div>
                            <div class="product-icon">
                                <h3><strong>{$LANG.navservices}</strong> - {$product}</h3>
                                <h5>{$groupname}</h5>
                                <a class="inline-text" href="http://{$domain}"><h4>{$domain}</h4></a> <a class="inline-text" href="clientarea.php?action=domaindetails&id={$domainId}"><img src="https://portal.qwords.com/templates/qwordsv2/img/list manage produk/settings.png"></a>
                            </div>
                            <div class="product-icon">
                                {if $billingcycle != $LANG.orderpaymenttermonetime && $billingcycle != $LANG.orderfree}
                                    <h5 class="inline-text">{$LANG.recurringamount}:</h5> <h5 class="inline-text">{$recurringamount}</h5>
                                {/if}
                            </div>
                            <div class="product-icon">
                                {if $suspendreason}
                                    <h5>{$LANG.suspendreason}</h5>
                                    {$suspendreason}
                                {/if}
                                {if $customfields && $isShowAuction}
                                <p style="margin-top:1em">
                                    {foreach from=$customfields item=field}
                                        {if $field.id == 1215 }
                                            <a class="btn btn-primary" href="https://portal.qwords.com/index.php?m=auction&domain={$field.value}"> Lelang Domain </a>
                                        {/if}
                                    {/foreach}
                                </p>
                                {/if}
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="product-status">
                            <div class="productdetailicon">
                                <img src="https://portal.qwords.com/templates/qwordsv2/img/list manage produk/calendar.png">
                            </div>
                            <div class="row product-icon">
                                <div class="col-md-6" style="padding: 0px !important;">
                                     <h5>{$LANG.clientareahostingnextduedate}</h5>
                                    {$nextduedate}
                                </div>
                                <div class="col-md-6" style="padding: 0px !important;">
                                     <h5>{$LANG.orderbillingcycle}:</h5>
                                    {$billingcycle}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                    <div class="ulproductdetail" style="padding:24px">
                        <div class="panel-heading" style="margin-bottom: 20px;">
                    		<h3 class="panel-title">
                    			Panel/Informasi Tambahan
                    		</h3>
                    	</div>
                        <h5 align="left" style="margin-bottom:15px">SSL Certificate Status : <strong>Not Created</strong></h5>
                        <div style="display:none; grid-template-columns: 200px auto; text-align:left; margin-bottom:15px;width:70%">
                            <p>Contact:</p>
                            <div style="display:flex; justify-content:space-between">
                                {if $myContact}
                                 <p style="margin-bottom:0px">{$myContact}</p>
                                {else}
                                <select id="listContact" onchange="handlerChangeContact(event)" name="contact" style="padding:8px; border-radius:8px; border:1px solid #ced4da; width:70%">
                                    {foreach $dataContact as $cont}
                                        <option value="{$cont.client_contact_id}" {if $loop.first}selected{/if}>{$cont.contact_email}</option>
                                    {/foreach}
                                </select>
                                {/if}
                                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-contact">Add Contact</button>
                            </div>
                        </div>
                        
                        <form method="post" action="clientarea.php?action=productdetails&id={$id}">
                            <input type="hidden" value="{$myContact}" name="contact" id="contactId"/>
                            <input type="hidden" value="1" name="disable"/>
                            <input type="hidden" value="createSSL" name="createSSL"/>
                            {if !$hostname}
                            <div style="display:grid; grid-template-columns: 200px auto; text-align:left; margin-bottom:15px;width:70%">
                                <p>Domain Name (FQDN):</p>
                                <div>
                                    <input type="text" placeholder="Masukkan domain FQDN Anda" id="domainFqdn" name="hostname" style="width:100%; border-radius:8px; border:1px solid #ced4da; padding:8px;" />
                                </div>
                            </div>
                            {/if}
                            
                            <div style="display:grid; grid-template-columns: 200px auto; text-align:left; margin-bottom:15px;width:70%">
                                <p>CSR:</p>
                                <div>
                                    <textarea rows="4" style="width:100%; border-radius:8px; border:1px solid #ced4da" id="csrText" name="csr" placeholder="Anda dapat paste CSR disini"></textarea>
                                    <div class="d-flex flex-column">
                                        <p style="align-self:center;margin-bottom:0px">Anda dapat generate CSR pada button berikut:</p>
                                        <a style="width:fit-content; margin-left:10px" class="btn btn-default" onclick="fetchDataCountry()" data-toggle="modal" data-target="#modal-csr">disini</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div style="display:grid; grid-template-columns: 200px auto; text-align:left;width:70%; margin-bottom:15px">
                                <p>Validation Method:</p>
                                <select id="validationMethod" name="validation_method" onchange="handlerChangeValidation(event)" style="padding:8px; border-radius:8px; border:1px solid #ced4da">
                                    <option value="email" selected>Email</option>
                                    <option value="dns">DNS</option>
                                </select>
                            </div>
                            
                            {if !$approval_email}
                            <div id="approval" style="display:grid; grid-template-columns: 200px auto; text-align:left;width:70%; margin-bottom:15px">
                                <p>Approval Email:</p>
                                <select id="approvalEmail" name="approval_email" style="padding:8px; border-radius:8px; border:1px solid #ced4da">
                                    {foreach $dataApproval as $approval}
                                        <option value="{$approval}" {if $loop.first}selected{/if}>{$approval}</option>
                                    {/foreach}
                                </select>
                            </div>
                            {/if}
                            
                            {if $disable === true}
                                <div style="width:70%; text-align:right; margin-top:15px; padding-bottom:25px">
                                    <button type="submit" disabled style="padding:8px 10px;border:0px solid transparent;color:white; background-color:#f7863b;border-radius:8px;">Submit</button>
                                </div>
                            {else}
                                <div style="width:70%; text-align:right; margin-top:15px; padding-bottom:25px">
                                    <button type="submit" onclick="disableButtonAndSubmit(this)" style="padding:8px 10px;border:0px solid transparent;color:white; background-color:#f7863b;border-radius:8px;">Submit</button>
                                </div>
                            {/if}
                        </form>
                    </div>
                    </div>
                </div>
                
            </div>

    </div>
    
    <div class="modal fade" id="modal-contact" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-weight:bold">Create Contact</h4>
              </div>
              <center><div class="msg-alert"></div></center>
              <form method="post" action="clientarea.php?action=productdetails&id={$id}">
                    <input type="hidden" value="createContact" name="createContact"/>
                  <div class="modal-body">
                    <div>
                        <p>Company Name:</p>
                        <input type="text" name="company_name" placeholder="Enter your company name" style="width:100%; border-radius:8px; border:1px solid #ced4da; padding:8px; margin-bottom:10px"/>
                        <div class="d-flex">
                            <div style="width:50%;padding-right:5px">
                                <p>First Name:</p>
                                <input type="text" name="first_name" placeholder="Enter your first name" style="width:100%; border-radius:8px; border:1px solid #ced4da; padding:8px; margin-bottom:10px"/>
                            </div>
                            <div style="width:50%;padding-left:5px">
                                <p>Last Name:</p>
                                <input type="text" name="last_name" placeholder="Enter your last name" style="width:100%; border-radius:8px; border:1px solid #ced4da; padding:8px; margin-bottom:10px"/>
                            </div>
                        </div>
                        
                        <div class="d-flex">
                            <div style="width:50%;padding-right:5px">
                                <p>Gender:</p>
                                <select name="gender" style="width:100%;padding:8px; border-radius:8px; border:1px solid #ced4da">
                                    <option value="M" selected>Male</option>
                                    <option value="F">Female</option>
                                </select>
                            </div>
                            <div style="width:50%;padding-left:5px">
                                <p>Telephone Number:</p>
                                <input type="text" name="telephone_number" placeholder="Enter your telephone number" style="width:100%; border-radius:8px; border:1px solid #ced4da; padding:8px; margin-bottom:10px"/>
                            </div>
                        </div>
                        
                        <div class="d-flex">
                            <div style="width:50%;padding-right:5px">
                                <p>Street:</p>
                                <input type="text" name="street" placeholder="Enter your street" style="width:100%; border-radius:8px; border:1px solid #ced4da; padding:8px; margin-bottom:10px"/>
                            </div>
                            <div style="width:50%;padding-left:5px">
                                <p>Number:</p>
                                <input type="text" name="number" placeholder="Enter your number" style="width:100%; border-radius:8px; border:1px solid #ced4da; padding:8px; margin-bottom:10px"/>
                            </div>
                        </div>
                        
                        <div class="d-flex">
                            <div style="width:50%;padding-right:5px">
                                <p>Country:</p>
                                <select id="listCountry" name="country" style="padding:8px; border-radius:8px; border:1px solid #ced4da; width:100%; margin-bottom:10px">
                                </select>
                            </div>
                            <div style="width:50%;padding-left:5px">
                                <p>Language:</p>
                                <select id="listLanguage" name="language" style="padding:8px; border-radius:8px; border:1px solid #ced4da; width:100%; margin-bottom:10px">
                                    {foreach $dataLanguage as $language}
                                        <option value="{$language.code}" {if $loop.first}selected{/if}>{$language.name}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                        
                        <div class="d-flex">
                            <div style="width:50%;padding-right:5px">
                                <p>State:</p>
                                <input type="text" name="state" placeholder="Enter your state" style="width:100%; border-radius:8px; border:1px solid #ced4da; padding:8px; margin-bottom:10px"/>
                            </div>
                            <div style="width:50%;padding-left:5px">
                                <p>City:</p>
                                <input type="text" name="city" placeholder="Enter your city" style="width:100%; border-radius:8px; border:1px solid #ced4da; padding:8px; margin-bottom:10px"/>
                            </div>
                        </div>
                        
                        <div class="d-flex">
                            <div style="width:50%;padding-right:5px">
                                <p>Zip Code:</p>
                                <input type="number" name="zip_code" placeholder="Enter your zip code" style="width:100%; border-radius:8px; border:1px solid #ced4da; padding:8px; margin-bottom:10px"/>
                            </div>
                            <div style="width:50%;padding-left:5px">
                                <p>Email:</p>
                                <input type="email" name="email" placeholder="Enter your email" style="width:100%; border-radius:8px; border:1px solid #ced4da; padding:8px; margin-bottom:10px"/>
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button class="btn btn-default" type="submit">Submit</button>
                  </div>
              </form>
        </div>
     </div>
    </div>

    <div class="modal fade" id="modal-csr" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" style="font-weight:bold">Generate CSR</h4>
                </div>
                <center><div class="msg-alert"></div></center>
                <div class="modal-body">
                    <div id="generateNewCsr">
                        <div class="d-flex">
                            <div class="d-flex" style="width:100%;margin-bottom:10px">
                                <p>Domain:</p>
                                <input type="text" id="hostnameCsr" disabled name="hostname" placeholder="Enter your domain" style="width:100%; border-radius:8px; border:1px solid #ced4da; padding:8px;" value="{$hostname}" required/>
                            </div>
                            <div style="width:100%;margin-bottom:10px">
                                <p>Organization Name:</p>
                                <input type="text" id="organizationCsr" name="organization" placeholder="Enter your organization name" style="width:100%; border-radius:8px; border:1px solid #ced4da; padding:8px;" value="{$company}" required/>
                                <small style="font-size:10px">The exact legal name of the organization. Example: "PT. Example". If a registered organization name does not exist, you should enter the full name of the individual.</small>
                            </div>
                        </div>
                        
                        <div class="d-flex">
                            <div style="width:100%;margin-bottom:10px">
                                <p>Unit:</p>
                                <input type="text" id="unitCsr" name="unit" placeholder="Enter your unit" style="width:100%; border-radius:8px; border:1px solid #ced4da; padding:8px;" required/>
                                <small style="font-size:10px">This is the department within the organization which you want to appear in the certificate. It will be listed in the certificates subject as Organizational Unit, or "OU".</small>
                            </div>
                            <div style="width:100%;margin-bottom:10px">
                                <p>Email:</p>
                                <input type="text" id="emailCsr" name="email" placeholder="Enter your email" style="width:100%; border-radius:8px; border:1px solid #ced4da; padding:8px;" value="{$email}" required/>
                                <small style="font-size:10px">E-mail address of the responsible person.</small>
                            </div>
                        </div>
                        
                        <div class="d-flex">
                            <div style="width:100%;margin-bottom:10px">
                                <p>Country:</p>
                                <input type="text" id="inputCountryCsr" name="country" placeholder="Enter your Country" style="width:100%; border-radius:8px; border:1px solid #ced4da; padding:8px;"/>
                                <select id="listCountryCsr" name="country" onchange="fetchDataState(event)" style="padding:8px; border-radius:8px; border:1px solid #ced4da; width:100%; display: none;">
                                </select>
                                <small style="font-size:10px">The country where the organization is legally located.</small>
                                
                                <small style="font-size:10px;float:right" id="ldco">Loading country...</small>
                            </div>
                            <div style="width:100%; margin-bottom:10px">
                                <p>State/Region/Province:</p>
                                <input type="text" id="inputStateCsr" name="state" placeholder="Enter your state/province/region" style="width:100%; border-radius:8px; border:1px solid #ced4da; padding:8px;"/>
                                <select id="listStateCsr" onchange="fetchDataCity(event)" name="state" style="padding:8px; border-radius:8px; border:1px solid #ced4da; width:100%; display: none;">
                                </select>
                                <small style="font-size:10px">The state or province where the organization is legally located.</small>
                                <small style="font-size:10px;float:right" id="lds">Loading state...</small>
                            </div>
                        </div>
                        
                        <div class="d-flex">
                                <div style="width:100%; margin-bottom:10px">
                                    <p>City:</p>
                                    <input type="text" id="inputCityCsr" name="city" placeholder="Enter your city" style="width:100%; border-radius:8px; border:1px solid #ced4da; padding:8px;"/>
                                    <select id="listCityCsr" name="city" style="padding:8px; border-radius:8px; border:1px solid #ced4da; width:100%; display: none;">
                                    </select>
                                    <small style="font-size:10px">The city where the organization is legally located.</small>
                                    <small style="font-size:10px;float:right" id="ldcit">Loading city...</small>
                                </div>
                            <div style="width:100%; margin-bottom:10px">
                                <p>Key Size:</p>
                                <select name="bits" id="bitsCsr" style="width:100%;padding:8px; border-radius:8px; border:1px solid #ced4da" required>
                                    <option value="2048" selected>2048 Bit</option>
                                    <option value="4096">4096 Bit</option>
                                </select>
                                <small style="font-size:10px">A key size less than 2048 is considered insecure and will not be accepted by most certificate vendors.</small>
                            </div>
                        </div>
                    </div>
                    <div id="loaderCsr" style="display: none;">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div id="hasilCsr" style="display: none;">
                        <div>
                            <p>CSR:</p>
                            <textarea rows="4" style="width:100%; border-radius:8px; border:1px solid #ced4da" id="generatedCsr"></textarea>
                        </div>
                        <div>
                            <p>Key:</p>
                            <textarea rows="4" style="width:100%; border-radius:8px; border:1px solid #ced4da" id="generatedKey"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button class="btn btn-default" onclick="generateCSR()" id="submitCsr" type="submit">Submit</button>
                </div>
          </div>
       </div>
      </div>
<script>
    const id = '{$id}';

    function handlerChangeValidation(e){
        const el_approve =  document.getElementById("approval");
        if(e.target.value !== "email"){
            el_approve.style.display = "none";
        }else{
            el_approve.style.display = "grid";
        }
    }

    function disableButtonAndSubmit(button) {
        let contactElement = document.getElementById("contactId");
        let domainElement = document.getElementById("domainFqdn");
        let csrElement = document.getElementById("csrText");
        let validationMethodElement = document.getElementById("validationMethod");
        let approvalEmailElement = document.getElementById("approvalEmail");
        
        // if (contactElement && !contactElement.value) {
        //     alert(`Please provide a value for Contact.`);
        //     return;
        // }
        
        if (domainElement && !domainElement.value) {
            alert(`Please provide a value for Domain.`);
            return;
        }
        
        if (csrElement && !csrElement.value) {
            alert(`Please provide a value for csr.`);
            return;
        }
        
        if (validationMethodElement && !validationMethodElement.value) {
            alert(`Please provide a value for validation method.`);
            return;
        }
        
        if (approvalEmailElement && !approvalEmailElement.value) {
            alert(`Please provide a value for approval email.`);
            return;
        }
        
        button.disabled = true; 
        button.innerHTML = 'Loading...';
        button.form.submit();
    }
    
    function handlerChangeContact(e){
        let contactElement = document.getElementById("contactId");
        contactElement.value = e.target.value;
    }

    function fetchDataCountry(){
        const selectElementCountry = document.getElementById("listCountryCsr");
        const inputElementCountry = document.getElementById("inputCountryCsr");
        
        
        var country_length =  jQuery("#listCountryCsr").find('option').length; 
        if (country_length > 2){
            return ;
        }
        
        selectElementCountry.disabled = true;
        fetch('/modules/servers/aksaradatassl/apis/getLocation.php?type=country&data=all&serviceId=' +id)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error!`);
                }
                
                selectElementCountry.disabled = false
                return response.json();
            })
            .then(data => {
                selectElementCountry.innerHTML = "";

                data.forEach(item => {
                    const option = document.createElement("option");
                    option.value = item.iso; 
                    option.textContent = item.name; 

                    selectElementCountry.appendChild(option);
                });
                
                selectElementCountry.value = "ID"
                jQuery(selectElementCountry).trigger("change")
                inputElementCountry.disabled = false
                
                inputElementCountry.style.display = "none";
                selectElementCountry.style.display = "block";
                jQuery('#ldco').hide();
            })
            .catch(error => {
                console.error('Fetch error:', error);
            });
    }

    function fetchDataState(e){
        const selectElementState = document.getElementById("listStateCsr");
        const inputElementState = document.getElementById("inputStateCsr");
        const countryId = e.target.value;
        
        selectElementState.disabled = true
        
        fetch('/modules/servers/aksaradatassl/apis/getLocation.php?type=state&data=' +countryId +'&serviceId=' +id)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error!`);
                }
                selectElementState.disabled = false
                return response.json();
            })
            .then(data => {
                
                
                selectElementState.innerHTML = "";

                data.forEach(item => {
                    const option = document.createElement("option");
                    option.value = item.id; 
                    option.textContent = item.name; 

                    selectElementState.appendChild(option);
                });
                
                if (!selectElementState.value == 1){
                    console.log(selectElementState.value)
                    selectElementState.value = data[0].item.id;
                }
                jQuery(selectElementState).trigger("change")
                selectElementState.disabled = false
                
                inputElementState.style.display = "none";
                selectElementState.style.display = "block";
                jQuery('#lds').hide();
            })
            .catch(error => {
                console.error('Fetch error:', error);
            });
    }
    
    function fetchDataCity(e){
        const selectElementCity = document.getElementById("listCityCsr");
        const inputElementCity = document.getElementById("inputCityCsr");
        const stateId = e.target.value;
        

        selectElementCity.disabled = true
        fetch('/modules/servers/aksaradatassl/apis/getLocation.php?type=city&data=' +stateId +'&serviceId=' +id)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error!`);
                }
                selectElementCity.disabled = false
                return response.json();
            })
            .then(data => {
                selectElementCity.innerHTML = "";

                data.forEach(item => {
                    const option = document.createElement("option");
                    option.value = item.name; 
                    option.textContent = item.name; 

                    selectElementCity.appendChild(option);
                });
                
                inputElementCity.style.display = "none";
                selectElementCity.style.display = "block";
                
                // console.log(selectElementCity.value)
                if (!selectElementCity.value){
                    selectElementCity.value = data[0].item.name;
                }
                jQuery(selectElementCity).trigger("change");
                selectElementCity.disabled = false;
                jQuery('#ldcit').hide();
            })
            .catch(error => {
                console.error('Fetch error:', error);
            });
    }

    function generateCSR(){
        let elementNewCsr = document.getElementById("generateNewCsr");
        let elementLoadingCsr = document.getElementById("loaderCsr");
        let buttonCsr = document.getElementById("submitCsr");
        let elementHasilCsr = document.getElementById("hasilCsr");
        let hostname = document.getElementById("hostnameCsr").value;
        let organization = document.getElementById("organizationCsr").value;
        let unit = document.getElementById("unitCsr").value;
        let email = document.getElementById("emailCsr").value;
        let countryInput = document.getElementById("inputCountryCsr").value;
        let countrySelect = document.getElementById("listCountryCsr").value;
        let stateInput = document.getElementById("inputStateCsr").value;
        let stateSelect = document.getElementById("listStateCsr").selectedOptions[0].textContent;
        let cityInput = document.getElementById("inputCityCsr").value;
        let citySelect = document.getElementById("listCityCsr").value;
        let bits = document.getElementById("bitsCsr").value;
        let country = '';
        let state = '';
        let city = '';

        if(!hostname){
            alert(`Please provide a value for Domain.`);
            return;
        }

        if(!organization){
            alert(`Please provide a value for Organization Name.`);
            return;
        }

        if(!unit){
            alert(`Please provide a value for Unit.`);
            return;
        }

        if(!email){
            alert(`Please provide a value for Email.`);
            return;
        }

        if(!countryInput && !countrySelect){
            alert(`Please provide a value for Country.`);
            return;
        }

        if(!stateInput && !stateSelect){
            alert(`Please provide a value for State/Province/Region.`);
            return;
        }

        if(!cityInput && !citySelect){
            alert(`Please provide a value for City.`);
            return;
        }

        if(!bits){
            alert(`Please provide a value for Bits.`);
            return;
        }

        elementNewCsr.style.display = "none";
        elementLoadingCsr.style.display = "block";

        if(!countryInput){
            country = countrySelect;
        }else if(!countrySelect){
            country = countryInput;
        }

        if(!stateInput){
            state = stateSelect;
        }else if(!stateSelect){
            state = stateInput;
        }

        if(!cityInput){
            city = citySelect;
        }else if(!citySelect){
            city = cityInput;
        }


        fetch('/modules/servers/aksaradatassl/apis/generateCsr.php?hostname=' +hostname +'&organization=' +organization +'&unit=' +unit +'&email=' +email +'&country=' +country +'&state=' +state +'&city=' +city +'&bits=' +bits +'&serviceId=' +id)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error!`);
                }
                
                return response.json();
            })
            .then(data => {
                let elGeneratedCsr = document.getElementById("generatedCsr");
                let elGeneratedKey = document.getElementById("generatedKey");
                let elCsr = document.getElementById("csrText");
                
                elGeneratedCsr.value = data.csr;
                elGeneratedKey.value = data.key;
                elCsr.value = data.csr;
                
                buttonCsr.style.display = "none";
                elementLoadingCsr.style.display = "none";
                elementHasilCsr.style.display = "block"
            })
            .catch(error => {
                console.error('Fetch error:', error);
            });
    }
</script>

