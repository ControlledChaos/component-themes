<?php
$row_component = function( $props, $children ) {
	$class_name = ct_get_value( $props, 'className', '' );
	$new_props = $props;
	unset( $new_props['className'] );
	unset( $new_props['children'] );
	$new_children = React::mapChildren( $children, function( $child ) use ( &$new_props ) {
		return React::cloneElement( $child, $new_props );
	} );
	return React::createElement( 'div', [ 'className' => $class_name ], $new_children );
};

$styled = Component_Themes::style_component( $row_component, '
.RowComponent {
	display: flex;
	justify-content: space-between;
}' );

Component_Themes::register_component( 'RowComponent', $styled );
