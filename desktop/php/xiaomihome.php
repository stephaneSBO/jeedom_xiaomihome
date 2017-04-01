<?php

if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('xiaomihome');
sendVarToJS('eqType', 'xiaomihome');
$eqLogics = eqLogic::byType('xiaomihome');
?>

<div class="row row-overflow">
    <div class="col-lg-2 col-sm-3 col-sm-4">
        <div class="bs-sidebar">
            <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
                <a class="btn btn-default eqLogicAction" style="width : 100%;margin-top : 5px;margin-bottom: 5px;" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter un équipement}}</a>

                <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
                <?php
                foreach ($eqLogics as $eqLogic) {
                    if ($eqLogic->getConfiguration('type') == 'aquara') {
                        echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
                    }
                }
                echo '<hr>';
                foreach ($eqLogics as $eqLogic) {
                    if ($eqLogic->getConfiguration('type') == 'yeelight') {
                        echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
                    }
                }
                echo '<hr>';
                foreach ($eqLogics as $eqLogic) {
                    if ($eqLogic->getConfiguration('type') != 'aquara' && $eqLogic->getConfiguration('type') != 'yeelight') {
                        echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
                    }
                }
                ?>
            </ul>
        </div>
    </div>

    <div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
        <legend><i class="fa fa-cog"></i>  {{Gestion}}</legend>
        <div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction" data-action="add" style="background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
				<center>
					<i class="fa fa-plus-circle" style="font-size : 6em;color:#00979c;"></i>
				</center>
				<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#00979c"><center>Ajouter</center></span>
			</div>
			<div class="cursor eqLogicAction discover" data-action="scanyeelight" style="background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">
				<center>
					<i class="icon jeedom2-bright4" style="font-size : 6em;color:#767676;"></i>
				</center>
				<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Scan Yeelight}}</center></span>
			</div>
            <div class="cursor eqLogicAction" data-action="gotoPluginConf" style="background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">
                <center>
                    <i class="fa fa-wrench" style="font-size : 6em;color:#767676;"></i>
                </center>
                <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Configuration}}</center></span>
            </div>
            <div class="cursor" id="bt_healthxiaomihome" style="background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
                <center>
                    <i class="fa fa-medkit" style="font-size : 6em;color:#767676;"></i>
                </center>
                <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Santé}}</center></span>
            </div>
        </div>

        <legend><i class="fa fa-home"></i>  {{Mes Aqara}}</legend>
        <?php
        $status = 0;
        foreach ($eqLogics as $eqLogic) {
            if ($eqLogic->getConfiguration('type') == 'aquara') {
                if ($status == 0) {echo '<div class="eqLogicThumbnailContainer">';}
                $status = 1;
                $opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
                echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff ; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
                echo "<center>";
                if (file_exists(dirname(__FILE__) . '/../../core/config/devices/' . $eqLogic->getConfiguration('model') . '/' . $eqLogic->getConfiguration('model') . '.png')) {
                    echo '<img src="plugins/xiaomihome/core/config/devices/' . $eqLogic->getConfiguration('model') . '/' . $eqLogic->getConfiguration('model') . '.png' . '" height="105" width="95" />';
                } else {
                    echo '<img src="' . $plugin->getPathImgIcon() . '" height="105" width="95" />';
                }
                echo "</center>";
                echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
                echo '</div>';
            }
        }
        if ($status == 1) {
            echo '</div>';
        } else {
            echo "<br/><br/><br/><center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>{{Aucun aquara détecté, démarrer un node pour ajout}}</span></center>";
        }
        ?>

        <legend><i class="icon jeedom2-bright4"></i>  {{Mes Yeelight}}</legend>
        <?php
        $status = 0;
        foreach ($eqLogics as $eqLogic) {
            if ($eqLogic->getConfiguration('type') == 'yeelight') {
                if ($status == 0) {echo '<div class="eqLogicThumbnailContainer">';}
                $status = 1;
                $opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
                echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff ; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
                echo "<center>";
                if (file_exists(dirname(__FILE__) . '/../../core/config/devices/' . $eqLogic->getConfiguration('model') . '/' . $eqLogic->getConfiguration('model') . '.png')) {
                    echo '<img src="plugins/xiaomihome/core/config/devices/' . $eqLogic->getConfiguration('model') . '/' . $eqLogic->getConfiguration('model') . '.png' . '" height="105" width="95" />';
                } else {
                    echo '<img src="' . $plugin->getPathImgIcon() . '" height="105" width="95" />';
                }
                echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
                echo '</div>';
            }
        }
        if ($status == 1) {
            echo '</div>';
        } else {
            echo "<br/><br/><br/><center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>{{Aucune Yeelight détectée, lancer un scan yeelight}}</span></center>";
        }
        ?>

        <legend><i class="fa fa-wifi"></i>  {{Mes Xiaomi Wifi}}</legend>
        <?php
        $status = 0;
        foreach ($eqLogics as $eqLogic) {
            if ($eqLogic->getConfiguration('type') == 'wifi') {
                if ($status == 0) {echo '<div class="eqLogicThumbnailContainer">';}
                $status = 1;
                $opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
                echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff ; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
                echo "<center>";
                echo '<img src="plugins/xiaomihome/core/config/devices/' . $eqLogic->getConfiguration('model') . '/' . $eqLogic->getConfiguration('model') . '.png' . '" height="105" width="95" />';                echo "</center>";
                echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
                echo '</div>';
            }
        }
        if ($status == 1) {
            echo '</div>';
        } else {
            echo "<br/><br/><br/><center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>{{Aucun Xiaomi Wifi, lancer un scan wifi}}</span></center>";
        }
        ?>

    </div>

    <div class="col-lg-10 col-md-9 col-sm-8 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
        <a class="btn btn-success eqLogicAction pull-right" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
        <a class="btn btn-danger eqLogicAction pull-right" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
        <a class="btn btn-default eqLogicAction pull-right" data-action="configure"><i class="fa fa-cogs"></i> {{Configuration avancée}}</a>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a></li>
            <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer"></i> {{Equipement}}</a></li>
            <li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>
        </ul>
        <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
            <div role="tabpanel" class="tab-pane active" id="eqlogictab">
			<div class="row">
				<div class="col-sm-6">
                <form class="form-horizontal">
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Nom de l'équipement}}</label>
                            <div class="col-sm-3">
                                <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                                <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement xiaomihome}}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" >{{Objet parent}}</label>
                            <div class="col-sm-3">
                                <select class="form-control eqLogicAttr" data-l1key="object_id">
                                    <option value="">{{Aucun}}</option>
                                    <?php
                                    foreach (object::all() as $object) {
                                        echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Catégorie}}</label>
                            <div class="col-sm-8">
                                <?php
                                foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                                    echo '<label class="checkbox-inline">';
                                    echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                                    echo '</label>';
                                }
                                ?>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" ></label>
                            <div class="col-sm-8">
                                <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
                                <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
                            </div>
                        </div>
						<div class="form-group" id="ipfield">
                            <label class="col-sm-3 control-label">{{Adresse Ip}}</label>
                            <div class="col-sm-6">
                                <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ipwifi" placeholder="Ip du device wifi"></span>
                            </div>
                        </div>
                        <div class="form-group" id="passfield">
                            <label class="col-sm-3 control-label">{{Password/Token}}</label>
                            <div class="col-sm-6">
                                <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="password" placeholder="Voir message en bleu"></span>
                            </div>
                        </div>
						</fieldset>
						</form>
						</div>
						<div class="col-sm-6">
							<a class="btn btn-danger btn-sm pull-right" id="bt_autoDetectModule"><i class="fa fa-search" title="{{Recréer les commandes}}"></i>  {{Recréer les commandes}}</a>
							<a class="btn btn-primary btn-sm eqLogicAction pull-right" id="btn_sync"><i class="fa fa-spinner" title="{{Récupérer les infos}}"></i> {{Récupérer les infos}}</a><br/><br/>
							<form class="form-horizontal">
							<fieldset>
							<div class="form-group">
								<label class="col-sm-2 control-label">{{Equipement}}</label>
								<div class="col-sm-8">
									<select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="model" id="modelfield">
									<option value="">Aucun</option>
									<?php
									$groups = array();
									
									foreach (xiaomihome::devicesParameters() as $key => $info) {
										if (isset($info['groupe'])) {
											$info['key'] = $key;
											if (!isset($groups[$info['groupe']])) {
												$groups[$info['groupe']][0] = $info;
											} else {
												array_push($groups[$info['groupe']], $info);
											}
										}
									}
									ksort($groups);
									foreach ($groups as $group) {
										usort($group, function ($a, $b) {
											return strcmp($a['name'], $b['name']);
										});
										foreach ($group as $key => $info) {
											if ($key == 0) {
												echo '<optgroup label="{{' . $info['groupe'] . '}}">';
											}
											echo '<option value="' . $info['key'] . '">' . $info['name'] . '</option>';
										}
										echo '</optgroup>';
									}
									?>
									</select>
								</div>
							</div>
							<div class="form-group" id="gatewayfield">
								<label class="col-sm-3 control-label">{{Gateway}}</label>
								<div class="col-sm-3">
									<span class="eqLogicAttr label label-default" data-l1key="configuration" data-l2key="gateway"></span>
								</div>
								<label class="col-sm-2 control-label">{{Type}}</label>
								<div class="col-sm-3">
									<span class="eqLogicAttr label label-default" data-l1key="configuration" data-l2key="type" id="typefield"></span>
								</div>
							</div>
	
							<div class="form-group"  id="idfield">
								<label class="col-sm-3 control-label">{{Identifiant}}</label>
								<div class="col-sm-3">
									<span class="eqLogicAttr label label-default" data-l1key="configuration" data-l2key="sid"></span>
								</div>
								<label class="col-sm-2 control-label">{{Identifiant court}}</label>
								<div class="col-sm-3">
									<span class="eqLogicAttr label label-default" data-l1key="configuration" data-l2key="short_id"></span>
								</div>
							</div>
	
							<div class="form-group" id="modefield">
								<label class="col-sm-3 control-label">{{Dernière Activité}}</label>
								<div class="col-sm-3">
									<span class="eqLogicAttr label label-default" data-l1key="configuration" data-l2key="lastCommunication"></span>
								</div>
							</div>
							<center>
								<img src="core/img/no_image.gif" data-original=".jpg" id="img_device" class="img-responsive" style="max-height : 250px;"  onerror="this.src='plugins/xiaomihome/doc/images/xiaomihome_icon.png'"/>
							</center>
                    </fieldset>
                </form>
            </div>
			</div>
			</div>
            <div role="tabpanel" class="tab-pane" id="commandtab">

                <table id="table_cmd" class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th style="width: 250px;">{{Nom}}</th>
                            <th style="width: 100px;">{{Type}}</th>
                            <th style="width: 100px;">{{Unité}}</th>
                            <th style="width: 150px;">{{Paramètres}}</th>
                            <th style="width: 100px;"></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

<script>

$( "#modelfield" ).change(function(){
    if ($('#modelfield').value() == 'gateway') {
        $('#passfield').show();
    } else {
        $('#passfield').hide();
    }
});

$( "#typefield" ).change(function(){
    if ($('#typefield').value() == 'aquara') {
        if ($('#modelfield').value() == 'gateway') {
            $('#passfield').show();
        } else {
            $('#passfield').hide();
        }
		$('#ipfield').hide();
    }
    if ($('#typefield').value() == 'yeelight') {
        $('#passfield').hide();
		$('#ipfield').hide();
    }
    if ($('#typefield').value() == 'wifi') {
        $('#passfield').show();
		$('#ipfield').show();
    }
});
</script>

<?php include_file('desktop', 'xiaomihome', 'js', 'xiaomihome'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>
