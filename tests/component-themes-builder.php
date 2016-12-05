<?php
use function Corretto\describe, Corretto\it, Corretto\expect, Corretto\beforeEach;

require( './server/class-component-themes.php' );

// @codingStandardsIgnoreStart
function Component_Themes_TextWidget( $props ) {
	// @codingStandardsIgnoreEnd
	$class = ct_get_value( $props, 'className', '' );
	$text = ct_get_value( $props, 'text', 'This is a text widget with no data!' );
	$color = ct_get_value( $props, 'color', 'default' );
	return React::createElement( 'div', [ 'className' => $class ], [ React::createElement( 'p', [], 'text is: ' . $text ), React::createElement( 'p', [], 'color is: ' . $color ) ] );
};

describe( 'Component_Themes_Builder', function() {
	describe( '#render()', function() {
		describe( 'for an unregistered componentType', function() {
			beforeEach( function( $c ) {
				$c->theme = [ 'name' => 'TestTheme', 'slug' => 'testtheme' ];
				$c->page = [ 'id' => 'helloWorld', 'componentType' => 'WeirdThing', 'props' => [ 'text' => 'hello world' ] ];
				$c->builder = Component_Themes_Builder::get_builder();
			} );

			it( 'mentions the undefined componentType', function( $c ) {
				$result = $c->builder->render( $c->theme, $c->page );
				expect( $result )->toContain( "'WeirdThing'" );
			} );
		} );

		describe( 'for a registered componentType', function() {
			beforeEach( function( $c ) {
				$c->theme = [ 'name' => 'TestTheme', 'slug' => 'testtheme' ];
				$c->page = [ 'id' => 'helloWorld', 'componentType' => 'TextWidget', 'props' => [ 'text' => 'hello world' ] ];
				$c->builder = Component_Themes_Builder::get_builder();
			} );

			it( 'includes the id as a className', function( $c ) {
				$result = $c->builder->render( $c->theme, $c->page );
				expect( $result )->toContain( 'helloWorld' );
			} );

			it( 'includes the componentType as a className', function( $c ) {
				$result = $c->builder->render( $c->theme, $c->page );
				expect( $result )->toContain( 'TextWidget' );
			} );

			it( 'includes the props passed in the object description', function( $c ) {
				$result = $c->builder->render( $c->theme, $c->page );
				expect( $result )->toContain( 'text is: hello world' );
			} );

			it( 'includes props not passed in the object description as falsy values', function( $c ) {
				$result = $c->builder->render( $c->theme, $c->page );
				expect( $result )->toContain( 'color is: default' );
			} );
		} );
	} );
} );
