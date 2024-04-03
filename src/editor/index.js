import { registerBlockVariation } from "@wordpress/blocks";
import {
	BlockControls,
	InspectorAdvancedControls,
} from "@wordpress/block-editor";
import {
	ToolbarButton,
	ToolbarGroup,
	ToggleControl,
} from "@wordpress/components";
import { addFilter } from "@wordpress/hooks";
import { createHigherOrderComponent } from "@wordpress/compose";

/**
 * Add the attribute to the block.
 * This is the attribute that will be saved to the database.
 *
 * @param {object} settings block settings
 * @param {string} name block name
 * @returns {object} modified settings
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/filters/block-filters/#blocks-registerblocktype
 */
addFilter(
	"blocks.registerBlockType",
	"wpdev/toggle-content-lock/attributes",
	function (settings, name) {
		if (name !== "core/group") {
			return settings;
		}

		return {
			...settings,
			attributes: {
				...settings.attributes,
				showContentLock: {
					type: "boolean",
					default: false,
				},
			},
		};
	},
);

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
				showContentLock: true,
				layout: { type: "constrained" },
				metadata: { name: "Inner Container" },
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
		attributes.templateLock === "contentOnly"
			? "Advanced Editing"
			: "Lock Content";

	return (
		<>
			{attributes.showContentLock && (
				<BlockControls>
					<ToolbarGroup>
						<ToolbarButton text={buttonText} onClick={toggleContentLock} />
					</ToolbarGroup>
				</BlockControls>
			)}
			<InspectorAdvancedControls>
				<ToggleControl
					label="Show Content Lock"
					checked={attributes.showContentLock}
					onChange={(showContentLock) => setAttributes({ showContentLock })}
				/>
			</InspectorAdvancedControls>
		</>
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
