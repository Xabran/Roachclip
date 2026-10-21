<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Photo
 * @version 		$Id: photo-entry.html.php 1339 2009-12-19 00:37:55Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{if Phpfox::getUserId() == 1}
{foreach from=$aPhotos item=aPhoto name=photos}
<div class="t_center{if $aPhoto.view_id == 1} row_moderate{/if}{if $aPhoto.is_featured} row_featured{/if}" style="width:31%; margin-bottom:10px; float:left; padding-top:5px; z-index:1; margin-right:2px;" id="js_photo_id_{$aPhoto.photo_id}">		
	<div style="position:relative; width:150px; margin:auto; min-height:90px; height:auto !important; height:90px;" class="js_outer_photo_div js_mp_fix_holder image_hover_holder">
			{if ($aPhoto.view_id == 1 && Phpfox::getUserParam('photo.can_approve_photos')) || ($aPhoto.user_id == Phpfox::getUserId() && (Phpfox::getUserParam('photo.can_edit_own_photo_album') || Phpfox::getUserParam('photo.can_edit_own_photo') || Phpfox::getUserParam('photo.can_delete_own_photo'))) || (Phpfox::getUserParam('photo.can_edit_other_photo_albums') || Phpfox::getUserParam('photo.can_edit_other_photo') || Phpfox::getUserParam('photo.can_delete_other_photos'))}
			<div class="image_hover_menu">
			{if $aPhoto.view_id == 1 && Phpfox::getUserParam('photo.can_approve_photos')}
				<div id="js_photo_approve_{$aPhoto.photo_id}"><a href="#" title="{phrase var='photo.approve_this_photo'}" onclick="$.ajaxCall('photo.approvePhoto', 'photo_id={$aPhoto.photo_id}'); return false;">{img theme='misc/accept.png' alt='' width='16' height='16'}</a></div>
			{/if}
			{if $aPhoto.album_id && ((Phpfox::getUserParam('photo.can_edit_own_photo_album') && $aPhoto.user_id == Phpfox::getUserId()) || Phpfox::getUserParam('photo.can_edit_other_photo_albums'))}
				<div class="js_photo_set_cover_div"{if $aPhoto.is_cover} style="display:none;"{/if}><a href="#?photo_id={$aPhoto.photo_id}&amp;album_id={$aPhoto.album_id}" title="{phrase var='photo.set_as_album_cover'}" class="js_photo_set_cover">{img theme='misc/photo.png' alt='' width='16' height='16'}</a></div>		
			{/if}
			{if (Phpfox::getUserParam('photo.can_edit_own_photo') && $aPhoto.user_id == Phpfox::getUserId()) || Phpfox::getUserParam('photo.can_edit_other_photo')}
				<a href="#" title="{phrase var='photo.edit_this_photo'}" onclick="$.ajaxCall('photo.editPhoto', 'photo_id={$aPhoto.photo_id}'); return false;">{img theme='misc/page_white_edit.png' alt='' width='16' height='16'}</a><br />
			{/if}
			{if $aCallback === null && Phpfox::getUserParam('photo.can_feature_photo')}
				<div id="js_photo_feature_{$aPhoto.photo_id}">
				{if $aPhoto.is_featured}
					<a href="#" title="{phrase var='photo.un_feature_this_photo'}" onclick="$.ajaxCall('photo.feature', 'photo_id={$aPhoto.photo_id}&amp;type=0'); return false;">{img theme='misc/photo_unfeature.png' alt='' width='16' height='16'}</a>
				{else}
					<a href="#" title="{phrase var='photo.feature_this_photo'}" onclick="$.ajaxCall('photo.feature', 'photo_id={$aPhoto.photo_id}&amp;type=1'); return false;">{img theme='misc/photo_feature.png' alt='' width='16' height='16'}</a>
				{/if}
				</div>
			{/if}			
			{if (Phpfox::getUserParam('photo.can_delete_own_photo') && $aPhoto.user_id == Phpfox::getUserId()) || Phpfox::getUserParam('photo.can_delete_other_photos')}
				<a href="#" title="{phrase var='photo.delete_this_photo'}" onclick="if (confirm('{phrase var='photo.are_you_sure' phpfox_squote=true}')) $.ajaxCall('photo.deletePhoto', 'photo_id={$aPhoto.photo_id}'); return false;">{img theme='misc/delete.png' alt='' width='16' height='16'}</a>
			{/if}
			</div>
			{/if}
			{plugin call='photo.template_default_block_photo_entry_tool'}
		{if ($aPhoto.mature == 0 || (($aPhoto.mature == 1 || $aPhoto.mature == 2) && Phpfox::getUserId() && Phpfox::getUserParam('photo.photo_mature_age_limit') <= Phpfox::getUserBy('age'))) || $aPhoto.user_id == Phpfox::getUserId()}
		<a href="{$aPhoto.link}" title="{if $aPhoto.is_featured}{phrase var='photo.featured_photo'}: {/if}{phrase var='photo.title_by_full_name' title=$aPhoto.title|clean full_name=$aPhoto.full_name|clean}">{img server_id=$aPhoto.server_id path='photo.url_photo' file=$aPhoto.destination suffix='_150' max_width=150 max_height=150 title=$aPhoto.title class='js_mp_fix_width'}</a>
		{else}
		<a href="{$aPhoto.link}"{if $aPhoto.mature == 1} onclick="tb_show('{phrase var='photo.warning' phpfox_squote=true}', $.ajaxBox('photo.warning', 'height=300&amp;width=350&amp;link={$aPhoto.link}')); return false;"{/if}>{img theme='misc/no_access.png' alt=''}</a>
		{/if}
		<div class="p_4 t_left">
			<a href="{$aPhoto.link}" id="js_photo_title_{$aPhoto.photo_id}">{$aPhoto.title|clean|shorten:45:'...'|split:20}</a>
			{if !defined('PHPFOX_IS_USER_PROFILE')}
			<div class="extra_info">
				{phrase var='photo.by_user_info' user_info=$aPhoto|user}
			</div>
			{/if}
			{if isset($sView) && $sView == 'top-rated'}
			<div class="p_2">
			{phrase var='photo.total_rating_out_of_5' total_rating=$aPhoto.total_rating|round}
			</div>
			{elseif isset($sView) && $sView == 'top-battle'}
			<div class="p_2">
			{phrase var='photo.total_battle_win_s' total_battle=$aPhoto.total_battle}
			</div>			
			{/if}
			{plugin call='photo.template_default_block_photo_entry_info'}
		</div>			
	</div>
</div>
{if is_int($phpfox.iteration.photos/3)}
<div class="clear"></div>
{/if}
{/foreach}
<div class="clear"></div>
<div class="t_right">
	{pager}
</div>
{/if}