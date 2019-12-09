<?php
if($_POST['isajax'] == 1) {
    $_address  = $_POST["address"];
    $_wallet   = $_POST["wallet"];
    $_storage  = $_POST["storage"];
    $_bandwidth      = $_POST["bandwidth"];
    $_emailId      = $_POST["email_val"];
    $_directory      = $_POST["directory"];
    $_identity_directory = $_POST['identityDirectory'];
    //Saving the data in docker-compose.yml
    $str=file_get_contents('/volume1/@appstore/StorJ/scripts/docker-compose_base.yml');
//echo $str;
//echo "$wallet";

// simple find and replace
    $str=str_replace("\${WALLET}", "$_wallet",$str);
    $str=str_replace("\${EMAIL}", "$_emailId",$str);
    $str=str_replace("\${ADDRESS}", "$_directory",$str);
    $str=str_replace("\${BANDWIDTH}", "$_bandwidth",$str);
    $str=str_replace("\${STORAGE}", "$_storage",$str);
    //echo $str;
// write to target file
    file_put_contents('/volume1/@appstore/StorJ/scripts/docker-compose.yml', $str);

//Chnaging permissions of the shell script
    shell_exec("chmod 777 /volume1/@appstore/StorJ/scripts/storagenodestart.sh 2>&1");
    shell_exec("chmod 777 /volume1/@appstore/StorJ/scripts/storagenodestop.sh 2>&1");
    //shell_exec("chmod 777 /volume1/@appstore/storj/scripts/storagenode.sh 2>&1");

    shell_exec("bash /volume1/@appstore/StorJ/scripts/storagenodestart.sh $_address $_wallet $_emailId $_bandwidth $_storage $_identity_directory $_directory");


  }else if($_POST['isstopAjax'] == 1){
   shell_exec("bash /volume1/@appstore/StorJ/scripts/storagenodestop.sh");
  } else if($_POST['isstartajax'] == 1) {
    $output = shell_exec("/etc/init.d/STORJ.sh is-running");
    if (!trim($output) == "") {
    echo $output;
    } else {
      echo $output;
  }
} else {
?>
<?php include 'header.php';?>
<link href="./resources/css/config.css" type="text/css" rel="stylesheet">
  <div>
    <nav class="navbar">
      <a class="navbar-brand" href="index.php"><img src="./resources/img/logo.svg" /></a>
    </nav>
    <div class="row">
      <?php include 'menu.php'; ?>
          <?php
          // $xyz = shell_exec("somefile.sh");
          //$xyz = "SOME SAMPLE OUTPUT TO CHECKED!!!";
          //echo $xyz ;
          $output = shell_exec("/etc/init.d/STORJ.sh is-authorized 2>&1");
          $output = FALSE;
          if ( $output ){
            header("Location: dashboard.php");
          } else {
            //header("Location: authorize.php");
          ?>
          <div class="col-10 config-page">
            <div class="container-fluid">
              <h2>Setup</h2>
              <a href=""><p class="header-link">Documentation ></p></a>
                <div class="row segment" id="identityrow">
                  <div class="column col-md-2"><div class="segment-icon identity_icon"></div>

                  </div>
                  <div class="column col-md-10">
                    <h4 class="segment-title">Identity</h4>
                    <p class="segment-msg">Every node ie required to have a unique identifier on the network. If you haven't already, get an authorization token. This is required to create an identity.</p>
                    <span id="idetityval"></span><span style="display:none;" id="editidentitybtn"><button class="segment-btn" data-toggle="modal" data-target="#identity">
                      Edit Identity Path
                    </button></span>
                    <button class="segment-btn" data-toggle="modal" data-target="#identity" id="identitybtn">
                    Set Identity Path
                    </button>
                    <div class="modal fade" id="identity" tabindex="-1" role="dialog" aria-labelledby="identity" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Create Identity</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p class="modal-input-title">Authorization Token</p>
                            <input class="modal-input" type="text" id="identity_token" name="identity_token" placeholder="/path/to/identity"/>
                            <p class="identity_token_msg msg" style="display:none;">This is required Field</p>
                            <span class="identity_note"><span>Note:</span> Creating identity can take several hours or even days, depending on your machines processing power & luck.</span>
                          </div>
                          <div class="modal-footer">
                            <button class="modal-btn" data-dismiss="modal">Close</button>
                            <button class="modal-btn" id="create_identity"> Set Identity Path</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- <div style="display:none" id="storjrows"> -->
                <div class="row segment">
                  <div class="column col-md-2"><div class="segment-icon port-icon"></div></div>
                  <div class="column col-md-10 segment-content">
                    <h4 class="segment-title">Port Forwarding</h4>
                    <p class="segment-msg">How a storage node communicates with others on the Storj network, even though it is behind a router. You need a dynamic DNS service to ensure your storage node is connected.</p>
                    <span id="externalAddressval"></span><span style="display:none;" id="editexternalAddressbtn"><button class="segment-btn" data-toggle="modal" data-target="#externalAddress">
                      Edit External Address
                    </button></span>
                    <button class="segment-btn" data-toggle="modal" data-target="#externalAddress" id="externalAddressbtn">
                      Add External Address
                    </button>
                    <div class="modal fade" id="externalAddress" tabindex="-1" role="dialog" aria-labelledby="externalAddress" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Port Forwarding</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p class="modal-input-title">Host Address</p>
                            <input class="modal-input" id="host_address" name="host_address" type="number" step="1" min="1"  value="28967" class="quantity" placeholder="domain.ddns.net: 28967"/>
                            <p class="host_token_msg msg" style="display:none;">Enter Valid Host Address</p>
                          </div>
                          <div class="modal-footer">
                            <button class="modal-btn" data-dismiss="modal">Close</button>
                            <button class="modal-btn" id="create_address">Set External Address</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row segment">
                  <div class="column col-md-2"><div class="segment-icon wallet-icon"></div></div>
                  <div class="column col-md-10 segment-content">
                    <h4 class="segment-title">Ethereum Wallet Address</h4>
                    <p class="segment-msg">In order to recieve and hold your STORJ toen payouts, you need an ERC-20 compatible wallet to wwhich you hold the private key yourself.</p>
                    <span id="wallettbtnval"></span><span style="display:none;" id="editwallettbtn"><button class="segment-btn" data-toggle="modal" data-target="#walletAddress">
                        Edit Wallett Address
                      </button></span>
                    <button class="segment-btn" data-toggle="modal" data-target="#walletAddress" id="addwallettbtn">
                      Add Wallett Address
                    </button>
                    <div class="modal fade" id="walletAddress" tabindex="-1" role="dialog" aria-labelledby="walletAddress" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Ethereum Wallet Address</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p class="modal-input-title">Wallet Address</p>
                            <input class="modal-input" name="Wallet Address" id="wallet_address" placeholder="Enetr Wallet Address"/>
                            <p class="wallet_token_msg msg" style="display:none;">This is required Field</p>
                          </div>
                          <div class="modal-footer">
                            <button class="modal-btn" data-dismiss="modal">Close</button>
                            <button class="modal-btn" id="create_wallet">Set Wallet Address</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row segment">
                  <div class="column col-md-2"><div class="segment-icon storage-icon"></div></div>
                  <div class="column col-md-10 segment-content">
                    <h4 class="segment-title">Storage Allocation</h4>
                    <p class="segment-msg">How much disk space you wnat to allocate to the Storj network</p>
                    <span id="storagebtnval"></span><span style="display:none;" id="editstoragebtn"><button class="segment-btn" data-toggle="modal" data-target="#storageAllocation">
                      Edit Storage Capacity
                    </button></span>
                    <button class="segment-btn" data-toggle="modal" data-target="#storageAllocation" id="addstoragebtn">
                      Set Storage Capacity
                    </button>
                    <div class="modal fade" id="storageAllocation" tabindex="-1" role="dialog" aria-labelledby="storageAllocation" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Storage Allocation</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p class="modal-input-title">Storage Allocation</p>
                            <input class="modal-input shorter" id="storage_allocate" name="storage_allocate" type="number" step="1" min="1" class="quantity" placeholder="Please enter only valid number"/>
                            <p class="modal-input-metric">GB</p>
                            <p class="storage_token_msg msg" style="display:none;">Minimum 1000 GB is required</p>
                          </div>
                          <div class="modal-footer">
                            <button class="modal-btn" data-dismiss="modal">Close</button>
                            <button class="modal-btn" id="allocate_storage">Set Storage Capacity</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row segment">
                  <div class="column col-md-2"><div class="segment-icon bandwidth-icon"></div></div>
                  <div class="column col-md-10 segment-content">
                    <h4 class="segment-title">Bandwidth Allocation</h4>
                    <p class="segment-msg">How much bandwidth can you allocate to the Storj network.</p>
                      <span id="bandwidthbtnval"></span><span style="display:none;" id="editbandwidthbtn"><button class="segment-btn" data-toggle="modal" data-target="#bandwidth">
                      Edit Bandwidth Allocation
                    </button></span>
                    <button class="segment-btn" data-toggle="modal" data-target="#bandwidth" id="addbandwidthbtn">
                      Set Bandwidth Allocation
                    </button>
                    <div class="modal fade" id="bandwidth" tabindex="-1" role="dialog" aria-labelledby="bandwidth" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Bandwidth Allocation</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p class="modal-input-title">Bandwidth Allocation</p>
                            <input style="width: 280px" class="modal-input shorter" id="bandwidth_allocation" name="bandwidth_allocation" type="number" step="1" min="1" class="quantity" placeholder="Please enter only valid number"/>
                            <p class="modal-input-metric">TB</p>
                            <p class="bandwidth_token_msg msg" style="display:none;">Minimum 1 TB is required</p>
                          </div>
                          <div class="modal-footer">
                            <button class="modal-btn" data-dismiss="modal">Close</button>
                            <button class="modal-btn" id="create_bandwidth">Set Bandwidth Allocation</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row segment">
                  <div class="column col-md-2"><div class="segment-icon email-icon"></div></div>
                  <div class="column col-md-10 segment-content">
                    <h4 class="segment-title">Email Address</h4>
                    <p class="segment-msg">How a storage node communicates with others on the Storj network, even though it is behind a router. You need a dynamic DNS service to ensure your storage node is connected.</p>
                    <span id="emailAddressval"></span><span style="display:none;" id="editemailAddressbtn"><button class="segment-btn" data-toggle="modal" data-target="#emailAddress">
                      Edit Email Address
                    </button></span>
                    <button class="segment-btn" data-toggle="modal" data-target="#emailAddress" id="emailAddressbtn">
                      Add Email Address
                    </button>
                    <div class="modal fade" id="emailAddress" tabindex="-1" role="dialog" aria-labelledby="email_address" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Email Address</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p class="modal-input-title">Email Address</p>
                            <input class="modal-input" id="email_address" name="email_address" type="email" placeholder="Email Address"/>
                            <p class="email_token_msg msg" style="display:none;">Enter a Valid Email address</p>
                          </div>
                          <div class="modal-footer">
                            <button class="modal-btn" data-dismiss="modal">Close</button>
                            <button class="modal-btn" id="create_emailaddress">Set Email Address</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row segment">
                  <div class="column col-md-2"><div class="segment-icon directory-icon"></div></div>
                  <div class="column col-md-10 segment-content">
                    <h4 class="segment-title">Storage Directory</h4>
                    <p class="segment-msg">The local directory where you want files to be stored on your hard drive for the network</p>
                      <span id="directorybtnval"></span><span style="display:none;" id="editdirectorybtn"><button class="segment-btn" data-toggle="modal" data-target="#directory">
                      Edit Storage Directory
                    </button></span>
                    <button class="segment-btn" data-toggle="modal" data-target="#directory" id="adddirectorybtn">
                      Set Storage Directory
                    </button>
                    <div class="modal fade" id="directory" tabindex="-1" role="dialog" aria-labelledby="directory" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="identity">Storage Directory</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p class="modal-input-title">Storage Directory</p>
                            <input class="modal-input" id="storage_directory" name="storage_directory" placeholder="/path/to/folder_to_share" />
                            <p class="directory_token_msg msg" style="display:none;">This is required Field</p>
                          </div>
                          <div class="modal-footer">
                            <button class="modal-btn" data-dismiss="modal">Close</button>
                            <button class="modal-btn" id="create_directory">Set Directory</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="bottom-buttons">
                  <button type="button" disabled class="stop-button" id="stopbtn">Stop My Storage Node</button>
                  <button type="button" class="start-button" id="startbtn">Start My Storage Node</button>
                </div>
              <!-- </div> -->
            </div>
          </div>
          <?php } ?>
  </div>
<?php include 'footer.php';?>
<!--<script src="./resources/js/jquery-3.1.1.min.js"></script>-->
<script type="text/javascript" src="./resources/js/config.js"></script>
<?php } ?>
