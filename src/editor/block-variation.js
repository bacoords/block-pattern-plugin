import { registerBlockVariation } from "@wordpress/blocks";
import domReady from "@wordpress/dom-ready";
import { __ } from "@wordpress/i18n";

domReady(() => {
	const postName = ""; // wp.data.select("core/editor").getCurrentPost();
	const postSlug = ""; // wp.data.select("core/editor").getCurrentPost().slug;

	console.log(wp.data.select("core/editor"));

	/**
	 * Registers a custom block variation for the Group block.
	 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-variations/
	 */
	registerBlockVariation("core/group", {
		name: "pattern-container",
		title: "Pattern Container",
		description: "Group block with locked content",
		attributes: {
			align: "full",
			tagName: "section",
			layout: { type: "default" },
			metadata: { name: "Pattern " + postName },
			className: "pattern-" + postSlug,
			style: {
				spacing: {
					padding: {
						top: "var:preset|spacing|40",
						bottom: "var:preset|spacing|40",
						left: "var:preset|spacing|20",
						right: "var:preset|spacing|20",
					},
				},
			},
		},
		innerBlocks: [
			[
				"core/group",
				{
					templateLock: "",
					showContentLock: true,
					layout: { type: "constrained" },
					metadata: { name: "Inner Container" },
				},
				[["core/paragraph", { placeholder: "Content locked" }]],
			],
		],
	});
});
