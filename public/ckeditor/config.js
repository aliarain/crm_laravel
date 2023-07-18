/**
 * @license Copyright (c) 2003-2021, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	config.colorButton_colors = 'd81324,222222'; //color pallet defined

	config.enterMode = 2; //disabled <p> completely
	config.enterMode = CKEDITOR.ENTER_BR // pressing the ENTER KEY input <br/>
	config.shiftEnterMode = CKEDITOR.ENTER_P; //pressing the SHIFT + ENTER KEYS input <p>
	config.autoParagraph = false; // stops automatic insertion of <p> on focu
};
