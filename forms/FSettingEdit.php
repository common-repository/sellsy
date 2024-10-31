<?php

namespace com\sellsy\sellsy\forms;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Form_SettingEdit extends \WP_Query
{
    private $_action;
    private $_returnUrl;

    public function __construct()
    {
        $this->_action      = $_SERVER["REQUEST_URI"];
        $this->_returnUrl   = SELLSY_URL_BACK_SOUS_MENU_3;
    }

    /**
     * retourn form
     * @param array $r
     */
    public function settingEdit($r)
    {
        ?>
        <form method="post" action="<?php echo $this->_action; ?>">
            <?php wp_nonce_field('form_nonce_setting_edit'); ?>
            <input type='hidden' name='form_id' value='<?php echo $r[0]->setting_id; ?>' />

            <div class="submit">
                <input type="submit" name="update" value="<?php _e('Update', PLUGIN_NOM_LANG); ?>" class="button button-primary" />
                <a href='<?php echo $this->_returnUrl; ?>' class='button'><?php _e('Back', PLUGIN_NOM_LANG); ?></a>
            </div>

            <div class="postbox " id="postexcerpt">
                <h3 class="hndle"><span><?php _e('Edit Sellsy API', PLUGIN_NOM_LANG); ?></span></h3>
                <div class="inside">

                    <a href="https://www.sellsy.fr/?_f=accountPreferences&action=development" target="_blank">
                        <?php _e('Get your API Keys', PLUGIN_NOM_LANG); ?>
                    </a>

                    <table class='table1'>
                        <tr>
                            <th>
                                <?php _e('Consumer token', PLUGIN_NOM_LANG); ?> :
                            </th>
                            <td>
                                <input type="text" name="form_consumer_token" value="<?php echo $r[0]->setting_consumer_token; ?>" />
                                <p class="description"><?php _e('Get this information on Sellsy', PLUGIN_NOM_LANG); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <?php _e('Consumer secret', PLUGIN_NOM_LANG); ?> :
                            </th>
                            <td>
                                <input type="text" name="form_consumer_secret" value="<?php echo $r[0]->setting_consumer_secret; ?>" />
                                <p class="description"><?php _e('Get this information on Sellsy', PLUGIN_NOM_LANG); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <?php _e('Utilisateur token', PLUGIN_NOM_LANG); ?> :
                            </th>
                            <td>
                                <input type="text" name="form_utilisateur_token" value="<?php echo $r[0]->setting_utilisateur_token; ?>" />
                                <p class="description"><?php _e('Get this information on Sellsy', PLUGIN_NOM_LANG); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <?php _e('Utilisateur secret', PLUGIN_NOM_LANG); ?> :
                            </th>
                            <td>
                                <input type="text" name="form_utilisateur_secret" value="<?php echo $r[0]->setting_utilisateur_secret; ?>" />
                                <p class="description"><?php _e('Get this information on Sellsy', PLUGIN_NOM_LANG); ?></p>
                            </td>
                        </tr>
                    </table>

                </div>
            </div><?php //postbox ?>




            <div class="postbox " id="postexcerpt">
                <h3 class="hndle"><span><?php _e('Clearbit', PLUGIN_NOM_LANG); ?></span></h3>
                <div class="inside">

                    <table class='table1'>
                        <tr>
                            <th>
                                <?php _e('API', PLUGIN_NOM_LANG); ?> :
                            </th>
                            <td>
                                <input type="text" name="form_clearbit_token" value="<?php echo $r[0]->setting_clearbit_token; ?>" />
                                <p class="description">
                                    <?php _e('Get this information on Clearbit', PLUGIN_NOM_LANG); ?> (<a href="https://dashboard.clearbit.com/api" target="_blank">https://dashboard.clearbit.com/api)</a>.<br>
                                    Ex : sk_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx.
                                </p>
                            </td>
                        </tr>
                    </table>

                </div>
            </div><?php //postbox ?>




            <div class="postbox " id="postexcerpt">
                <h3 class="hndle"><span><?php _e('General', PLUGIN_NOM_LANG); ?></span></h3>
                <div class="inside">

                    <table class='table1'>
                        <tr>
                            <td>
                                <?php _e('Tracking', PLUGIN_NOM_LANG); ?> :
                            </td>
                            <td>
                                <?php
                                $tracking_on = $tracking_off = "";
                                if ($r[0]->setting_tracking == 0) {
                                    $tracking_on = "checked";
                                } else {
                                    $tracking_off = "checked";
                                }
                                ?>

                                <input type="radio" id="tracking_on" name="form_tracking" value="0" <?php echo $tracking_on; ?> />
                                <label for="tracking_on">
                                    <img src='<?php echo SELLSY_PLUGIN_URL; ?>/images/icones/enabled.gif' />
                                </label>

                                <input type="radio" id="tracking_off" name="form_tracking" value="1" <?php echo $tracking_off; ?> />
                                <label for="tracking_off">
                                    <img src='<?php echo SELLSY_PLUGIN_URL; ?>/images/icones/disabled.gif' />
                                </label>

                                <p class="description"><?php
                                    _e("The tracking option allows you to find all of the visitor's browsing history on the Sellsy prospect sheet.", PLUGIN_NOM_LANG);
                                    echo '<br>';
                                    _e("If you enable this option, you must notify your visitors by communicating through your cookie policy.", PLUGIN_NOM_LANG);
                                ?></p>
                            </td>
                        </tr>
                    </table>

                </div>
            </div><?php //postbox ?>




            <div class="postbox " id="postexcerpt">
                <h3 class="hndle"><span><?php _e('Edit reCaptcha', PLUGIN_NOM_LANG); ?></span></h3>
                <div class="inside">

                    <a href="https://www.google.com/recaptcha/admin#list" target="_blank" title="reCaptcha">
                        https://www.google.com/recaptcha/admin#list
                    </a>

                    <table class='table1'>
                        <tr>
                            <th>
                                <?php _e('Key website', PLUGIN_NOM_LANG); ?> :
                            </th>
                            <td>
                                <input type="text" name="form_recaptcha_key_website" value="<?php echo $r[0]->setting_recaptcha_key_website; ?>" />
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <?php _e('Key secret', PLUGIN_NOM_LANG); ?> :
                            </th>
                            <td>
                                <input type="text" name="form_recaptcha_key_secret" value="<?php echo $r[0]->setting_recaptcha_key_secret; ?>" />
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <?php _e('reCAPTCHA', PLUGIN_NOM_LANG); ?> :
                            </th>
                            <td>
                                <?php
                                $version_2 = $version_3 = "";
                                if ($r[0]->setting_recaptcha_key_version == 3) {
                                    $version_3 = "checked";
                                } else {
                                    $version_2 = "checked";
                                }
                                ?>

                                <input type="radio" id="version_2" name="form_version" value="2" <?php echo $version_2; ?> />
                                <label for="version_2">Version 2</label>

                                <input type="radio" id="version_3" name="form_version" value="3" <?php echo $version_3; ?> />
                                <label for="version_3">Version 3</label>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <?php _e('Status', PLUGIN_NOM_LANG); ?> :
                            </th>
                            <td>
                                <?php
                                $status_on = $status_off = "";
                                if ($r[0]->setting_recaptcha_key_status == 0) {
                                    $status_on = "checked";
                                } else {
                                    $status_off = "checked";
                                }
                                ?>

                                <input type="radio" id="status_on" name="form_status" value="0" <?php echo $status_on; ?> />
                                <label for="status_on">
                                    <img src='<?php echo SELLSY_PLUGIN_URL; ?>/images/icones/enabled.gif' />
                                </label>

                                <input type="radio" id="status_off" name="form_status" value="1" <?php echo $status_off; ?> />
                                <label for="status_off">
                                    <img src='<?php echo SELLSY_PLUGIN_URL; ?>/images/icones/disabled.gif' />
                                </label>
                            </td>
                        </tr>
                    </table>

                </div>
            </div><?php //postbox ?>




            <div class="submit">
                <input type="submit" name="update" value="<?php _e('Update', PLUGIN_NOM_LANG); ?>" class="button button-primary" />
                <a href='<?php echo $this->_returnUrl; ?>' class='button'><?php _e('Back', PLUGIN_NOM_LANG); ?></a>
            </div>

        </form>

        <?php
    }
}//class
