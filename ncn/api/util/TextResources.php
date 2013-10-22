<?php
 
class TextResources {
	// v - validation
	const v_params_required = 'No required parameters set!';
	const v_wrong_params = 'Wrong parameters!';
	const v_unknown_type = 'Unknown type!';


	const v_album_exists = 'Album with given name already exists';
	const v_album_not_exists = 'There is no album with given ID';
	const v_album_parent_not_exists = 'There is no parent album with given ID';
	const v_album_media_already_exists = 'Media already exists in an album';
	const m_album_media_added = 'Media file has been added to an album';
	const m_album_created = 'A new album has been created';
	const m_album_updated = 'Album has been updated';

	const v_channel_exists = 'Channel with given name already exists';
	const v_channel_not_exists = 'There is no channel with given ID';
	const v_channel_album_already_exists = 'Album already pinned to a channel';
	const v_channel_album_no_relation = 'Album is not pinned to a channel';
	const v_channel_invalid_name = 'Invalid channel name';
	const m_channel_created = 'A new channel has been created';
	const m_channel_album_added = 'Album has been added to a channel';
	const m_channel_album_removed = 'Album to Channel relation successfully removed';
	const m_channel_updated = 'Channel has been updated';
	const m_channel_deleted = 'Channel has been successfully deleted';
	const m_channel_order_updated = 'Albums order has been successfully updated';

	const v_media_not_exists = 'There is no media with given ID';
	const m_media_updated = 'Media file metadata has been updated';

};