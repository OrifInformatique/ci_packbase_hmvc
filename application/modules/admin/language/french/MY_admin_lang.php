<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * French translations for admin module
 * 
 * @author      Orif
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (http://www.orif.ch)
 */

// Page titles
$lang['user_list_title']                = 'Liste des utilisateurs';
$lang['user_update_title']              = 'Modifier un utilisateur';
$lang['user_new_title']                 = 'Ajouter un utilisateur';
$lang['user_delete_title']              = 'Supprimer un utilisateur';
$lang['user_password_reset_title']      = 'Réinitialiser le mot de passe';

// Buttons
$lang['btn_add_m']                      = 'Nouveau';
$lang['btn_add_f']                      = 'Nouvelle';
$lang['btn_save']                       = 'Enregistrer';
$lang['btn_cancel']                     = 'Annuler';
$lang['btn_disable']                    = 'Désactiver';
$lang['btn_reactivate']                 = 'Réactiver';
$lang['btn_delete']                     = 'Supprimer';
$lang['btn_hard_delete']                = 'Supprimer définitivement';

// User form fields
$lang['user_name']                      = 'Nom d\'utilisateur';
$lang['user_usertype']                  = 'Type d\'utilisateur';
$lang['user_active']                    = 'Activé';
$lang['user_password']                  = 'Mot de passe';
$lang['user_password_again']            = 'Répéter le mot de passe';
$lang['deleted_users_display']          = 'Afficher les utilisateurs désactivés';

// Other texts
$lang['yes']                            = 'Oui';
$lang['no']                             = 'Non';
$lang['what_to_do']                     = 'Que souhaitez-vous faire ?';
$lang['user']                           = 'Utilisateur';
$lang['user_delete']                    = 'Désactiver ou supprimer cet utilisateur';
$lang['user_reactivate']                = 'Réactiver cet utilisateur';
$lang['user_disabled_info']             = 'Cet utilisateur est désactivé. Vous pouvez le réactiver en cliquant sur le lien correspondant.';
$lang['user_delete_explanation']        = 'La désactivation d\'un compte utilisateur permet de le rendre inutilisable tout en conservant ses informations dans les archives. '
                                         .'Cela permet notamment de garder l\'historique de ses actions.<br><br>'
                                         .'En cas de suppression définitive, toutes les informations concernant cet utilisateur seront supprimées.';
$lang['user_allready_disabled']         = 'Cet utilisateur est déjà désactivé. Voulez-vous le supprimer définitivement ?';

// Error messages
$lang['msg_err_user_not_exist']         = 'L\'utilisateur sélectionné n\'existe pas';
$lang['msg_err_user_already_inactive']  = 'L\'utilisateur est déjà inactif';
$lang['msg_err_user_already_active']    = 'L\'utilisateur est déjà actif';
$lang['msg_err_user_type_not_exist']    = 'Le type d\'utilisateur n\'existe pas';