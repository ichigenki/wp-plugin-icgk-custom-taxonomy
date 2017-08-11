<?php
/*
Plugin Name: ICGK Custom Taxonomy
Plugin URI: 
Description: カスタムタクソノミーを作成
Version: 1.0.0
Author: ICHIGENKI
Author URI: 
License: GPL2
*/

$page_title = 'ICGK Create/Edit Custom Taxonomy';
$menu_title = 'ICGK Custom Taxonomy';


// 管理メニューに追加するフック
add_action('admin_menu', 'icgk_custom_taxonomy_menu');

// 上のフックに対する action 関数
function icgk_custom_taxonomy_menu() {
	// 「設定」下に新しいサブメニューを追加
	add_options_page('ICGK Create/Edit Custom Taxonomy', 'ICGK Custom Taxonomy', 'manage_options', 'icgk-custom-taxonomy', 'icgk_custom_taxonomy_options' );
}

// メニュー項目をクリックした際に表示されるページ、または画面の HTML 出力を作成
// mt_settings_page() は Test Settings サブメニューのページコンテンツを表示
function icgk_custom_taxonomy_options() {

	// ユーザーが必要な権限を持つか確認する必要がある
	if ( !current_user_can('manage_options') ) {
	  wp_die( __('You do not have sufficient permissions to access this page.') );
	}

	// フィールドとオプション名の変数
	$option_name = 'icgk-custom-taxonomy';
	$hidden_field_name = 'mt_submit_hidden';
	global $option_data;
	// データベースから既存のオプション値を取得
	if ( get_option( $option_name ) ) {
		$option_data = get_option( $option_name );
	} else {
		$option_data = array();
	}

	// ユーザーが何か情報を POST したかどうかを確認
	// POST していれば、隠しフィールドに 'Y' が設定されている
	if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
		// POST されたデータを取得
		$option_data = $_POST[ 'icgk_taxonomy' ];
		// POST された値をデータベースに保存
		update_option( $option_name, $option_data );
		// 画面に「設定は保存されました」メッセージを表示
		//$saved = 'settings saved.'
		//_e($saved, 'menu-test' );
?>
<div class="updated"><p><strong>設定は保存されました</strong></p></div>
<?php
	}

	// ここで設定編集画面を表示
	echo '<div class="wrap">';
	// ヘッダー
	echo '<h2>ICGK Create/Edit Custom Taxonomy</h2>';
	// 設定用フォーム
?>
<br />
<hr />

<form name="form1" method="post" action="">
<!--<form method="post" action="options.php">-->
	<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
	<?php settings_fields( 'icgk-custom-taxonomy' ); ?>

<!--<p><?php _e("Favorite Color:", 'menu-test' ); ?> 
<input type="text" name="icgk_taxonomy" value="<?php echo $option_data; ?>" size="20">
</p>-->

<?php 
	$pn = 0;
	foreach ( $option_data as $data ) :
		if ( $data['name'] ) :
?>
	<h3>カスタム・タクソノミー (<?php echo $pn + 1; ?>)</h3>
	<table class="form-table">
		<tr valign="top">
		<th scope="row">名称</th>
		<td><input type="text" name="icgk_taxonomy[<?php echo $pn; ?>][name]" value="<?php echo $data['name']; ?>" />
		<p>半角英文字＋アンダーバー（削除する場合は空欄）</p></td>
		</tr>
		<tr valign="top">
		<th scope="row">表示名</th>
		<td><input type="text" name="icgk_taxonomy[<?php echo $pn; ?>][label]" value="<?php echo $data['label']; ?>" />
		<p>メニューに表示される名前（日本語可）</p></td>
		</tr>
		<tr valign="top">
		<th scope="row">スラッグ</th>
		<td><input type="text" name="icgk_taxonomy[<?php echo $pn; ?>][slug]" value="<?php echo $data['slug']; ?>" />
		<p>半角英文字＋ハイフン（空欄にした場合はタクソノミー名）</p></td>
		</tr>
		<tr valign="top">
		<th scope="row">階層構造</th>
		<td><label for="icgk_taxonomy_<?php echo $pn; ?>_hier_y"><input type="radio" name="icgk_taxonomy[<?php echo $pn; ?>][hier]" id="icgk_taxonomy_<?php echo $pn; ?>_hier_y" value="1"<?php if($data['hier'] == 1) echo ' checked="checked"'; ?> /> あり（カテゴリー型）</label>　　<label for="icgk_taxonomy_<?php echo $pn; ?>_hier_n"><input type="radio" name="icgk_taxonomy[<?php echo $pn; ?>][hier]" id="icgk_taxonomy_<?php echo $pn; ?>_hier_n" value="0"<?php if($data['hier'] == 0) echo ' checked="checked"'; ?> /> なし（タグ型）</label></td>
		</tr>
		<tr valign="top">
		<th scope="row">オブジェクトタイプ</th>
		<td><input type="text" name="icgk_taxonomy[<?php echo $pn; ?>][ptype]" value="<?php echo $data['ptype']; ?>" />
		<p>関連づけるポストタイプ</p></td>
		</tr>
	</table>
	<hr />
<?php 
			$pn++;
		endif;
	endforeach;
?>

	<div id="new-custom-post">
<?php
	if ( $pn == 0 ) {
		$name_val = 'newstype';
		$label_val = 'お知らせ分類';
		$slug_val = 'type';
		$ptype_val = 'news';
		$submit_style = ' style="display:none;"';
	} else {
		$name_val = '';
		$label_val = '';
		$slug_val = '';
		$hier_val = '';
		$ptype_val = '';
		$submit_style = '';
	}
	$nn = $pn + 1;
?>
		<div class="cpt-form" style="display:none;">
			<h3>新規カスタム・タクソノミー (<?php echo $nn; ?>)</h3>
			<table class="form-table">
				<tr valign="top">
				<th scope="row">名称</th>
				<td><input type="text" name="icgk_taxonomy[<?php echo $nn; ?>][name]" value="<?php echo $name_val; ?>" placeholder="" />
				<p>半角英文字＋アンダーバー</p></td>
				</tr>
				<tr valign="top">
				<th scope="row">表示名</th>
				<td><input type="text" name="icgk_taxonomy[<?php echo $nn; ?>][label]" value="<?php echo $label_val; ?>" placeholder="" />
				<p>メニューに表示される名前（日本語可）</p></td>
				</tr>
				<tr valign="top">
				<th scope="row">スラッグ</th>
				<td><input type="text" name="icgk_taxonomy[<?php echo $nn; ?>][slug]" value="<?php echo $slug_val; ?>" placeholder="" />
				<p>半角英文字＋ハイフン（空欄にした場合はタクソノミー名）</p></td>
				</tr>
				<tr valign="top">
				<th scope="row">階層構造</th>
				<td><label for="icgk_taxonomy_<?php echo $nn; ?>_hier_y"><input type="radio" name="icgk_taxonomy[<?php echo $nn; ?>][hier]" id="icgk_taxonomy_<?php echo $nn; ?>_hier_y" value="1" checked="checked" /> あり（カテゴリー型）</label>　　<label for="icgk_taxonomy_<?php echo $nn; ?>_hier_n"><input type="radio" name="icgk_taxonomy[<?php echo $nn; ?>][hier]" id="icgk_taxonomy_<?php echo $nn; ?>_hier_n" value="0" /> なし（タグ型）</label></td>
				</tr>
				<tr valign="top">
				<th scope="row">オブジェクトタイプ</th>
				<td><input type="text" name="icgk_taxonomy[<?php echo $nn; ?>][ptype]" value="<?php echo $ptype_val; ?>" placeholder="" />
				<p>関連づけるポストタイプ</p></td>
				</tr>
			</table>
			<hr />
		</div>
		<p class="cpt-button"><a href="javascript:showhideCPT();" class="button button-secondary">タクソノミーを追加</a></p>
		<p class="submit"<?php echo $submit_style; ?>><input type="submit" name="submit" id="submit" class="button button-primary" value="変更を保存" /></p>
	</div>

</form>
</div>
<script type="text/javascript">
<!--
	function showhideCPT() {
		jQuery('#new-custom-post .cpt-form').show();
		jQuery('#new-custom-post .cpt-button').hide();
		jQuery('#new-custom-post p.submit').show();
	}
-->
</script>
<?php
}



// カスタム・タクソノミーを作成

function create_icgk_tax() {
	register_taxonomy(
		'genre',
		'book',
		array(
			'label' => __( 'Genre' ),
			'rewrite' => array( 'slug' => 'genre' ),
			'hierarchical' => true,
		)
	);
}
add_action( 'init', 'create_icgk_tax' );

function icgk_create_custom_taxonomy() {
	// データベースから既存のオプション値を取得
	$option_name = 'icgk-custom-taxonomy';
	if ( get_option( $option_name ) ) {
		$option_data = get_option( $option_name );
	} else {
		$option_data = array();
	}

	$i = 0;
	foreach ( $option_data as $data ) :
		if ( $data['name'] ) :
			$name = $data['name'];
			$ptype = $data['ptype'];
			$label = $data['label'];
			$slug = $data['slug'];
			$hier = $data['hier'];
			if ( $slug == '' ) $slug = $name;

	register_taxonomy(
		$name,
		$ptype,
		array(
			'label' => __( $label ),
			'rewrite' => array( 'slug' => $slug ),
			'hierarchical' => $hier,
		)
	);
			$i++;
		endif;
	endforeach;
	flush_rewrite_rules();
}
// カスタムタクソノミー作成を実行
add_action( 'init', 'icgk_create_custom_taxonomy' );



//require_once( dirname(__FILE__) . '/register-taxonomy.php' );

