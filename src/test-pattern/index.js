/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
import { registerBlockType, registerBlockVariation } from "@wordpress/blocks";
import { useBlockProps, InnerBlocks } from "@wordpress/block-editor";
/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * All files containing `style` keyword are bundled together. The code used
 * gets applied both to the front of your site and to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import "./style.scss";

/**
 * Internal dependencies
 */
import Edit from "./edit";
import metadata from "./block.json";

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
registerBlockType(metadata.name, {
	/**
	 * @see ./edit.js
	 */
	edit: Edit,
});

registerBlockVariation("core/group", {
	name: "content-locked",
	title: "Content Locked",
	description: "Group block with locked content",
	attributes: {
		align: "full",
		tagName: "section",
		layout: { type: "default" },
		metadata: { name: "Pattern" },
		style: {
			spacing: {
				padding: {
					top: "var:preset|spacing|xl",
					bottom: "var:preset|spacing|xl",
					left: "var:preset|spacing|sm",
					right: "var:preset|spacing|sm",
				},
			},
		},
	},
	innerBlocks: [
		[
			"core/group",
			{
				templateLock: "contentOnly",
				layout: { type: "constrained" },
			},
			[["core/paragraph", { placeholder: "Content locked" }]],
		],
	],
});
