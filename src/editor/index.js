import { registerBlockVariation } from "@wordpress/blocks";
import { BlockControls } from "@wordpress/block-editor";
import { ToolbarButton } from "@wordpress/components";
import { addFilter } from "@wordpress/hooks";
import { createHigherOrderComponent } from "@wordpress/compose";

registerBlockVariation("core/group", {
	name: "pattern-container",
	title: "Pattern Container",
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
				templateLock: "",
				layout: { type: "constrained" },
			},
			[["core/paragraph", { placeholder: "Content locked" }]],
		],
	],
});

/**
 * BlockEdit
 *
 * a react component that will get mounted in the Editor when the block is
 * selected. It is recommended to use Slots like `BlockControls` or `InspectorControls`
 * in here to put settings into the blocks toolbar or sidebar.
 *
 * @param {object} props block props
 * @returns {JSX}
 */
function ContentToggleEdit(props) {
	const { attributes, setAttributes } = props;

	const toggleContentLock = () => {
		const isLocked = attributes.templateLock === "contentOnly";
		setAttributes({ templateLock: isLocked ? "" : "contentOnly" });
	};

	const buttonText =
		attributes.templateLock === "contentOnly" ? "Advanced" : "Lock Content";

	return (
		<BlockControls>
			<ToolbarButton text={buttonText} onClick={toggleContentLock} />
		</BlockControls>
	);
}

addFilter(
	"editor.BlockEdit",
	"wpdev/toggle-content-lock",
	createHigherOrderComponent((BlockEdit) => {
		return (props) => {
			if (props.name !== "core/group") {
				return <BlockEdit {...props} />;
			}

			return (
				<>
					<BlockEdit {...props} />
					<ContentToggleEdit {...props} />
				</>
			);
		};
	}),
);
