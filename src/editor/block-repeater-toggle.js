import {
	InspectorAdvancedControls,
	BlockControls,
	store as blockEditorStore,
} from "@wordpress/block-editor";
import {
	ToggleControl,
	ToolbarGroup,
	ToolbarButton,
} from "@wordpress/components";
import { addFilter } from "@wordpress/hooks";
import { createHigherOrderComponent } from "@wordpress/compose";
import { __ } from "@wordpress/i18n";
import { useEffect } from "@wordpress/element";
import { useSelect, useDispatch } from "@wordpress/data";
import { createBlock } from "@wordpress/blocks";
import { copy, trash } from "@wordpress/icons";

const ALLOWED_BLOCKS = [
	// "core/column",
	"core/button",
	"core/list-item",
	// "core/group",
];

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
	"wpdev/toggle-repeater/child-attributes",
	function (settings, name) {
		if (!ALLOWED_BLOCKS.includes(name)) {
			return settings;
		}

		return {
			...settings,
			attributes: {
				...settings.attributes,
				showRepeaterToggle: {
					type: "boolean",
					default: false,
					__experimentalRole: "content",
				},
			},
			usesContext: [...settings.usesContext, "wpdev/repeaterParentClientId"],
		};
	},
);

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
function RepeaterToggleEdit(props) {
	const { attributes, setAttributes, clientId, name } = props;

	// get function to select a given block
	const { selectBlock } = useDispatch(blockEditorStore);

	// get the insertBlock function from the block editor
	const { insertBlock } = useDispatch("core/block-editor");

	// get the current block's position in the parent block
	function getCurrentBlockPosition(block) {
		return block.clientId === clientId;
	}

	// get the parent block's clientId and parent block
	const { firstParentClientId } = useSelect((select) => {
		const { getBlockName, getBlockParents, getSelectedBlockClientId } =
			select(blockEditorStore);
		const selectedBlockClientId = getSelectedBlockClientId();
		const parents = getBlockParents(selectedBlockClientId);
		const _firstParentClientId = parents[parents.length - 1];
		return {
			firstParentClientId: _firstParentClientId,
		};
	}, []);

	// get the parent block's inner blocks
	const { parentInnerBlocks } = useSelect((select) => ({
		parentInnerBlocks:
			select("core/block-editor").getBlocks(firstParentClientId),
	}));

	const parseBlocks = (blocks, contentLock) => {
		blocks.forEach((block) => {
			if (block.name === "core/group" && block.attributes?.showContentLock) {
				block.attributes.templateLock = contentLock ? "" : "contentOnly";
				wp.data.dispatch("core/block-editor").updateBlock(block.clientId, {
					...block,
				});
			}

			if (block.innerBlocks) {
				parseBlocks(block.innerBlocks, contentLock);
			}
		});
	};

	// append a new  block to the the inner blocks list
	const duplicateBlock = () => {
		const block = createBlock(name, { showRepeaterToggle: true });
		const blocks = wp.data.select("core/block-editor").getBlocks();

		console.log(blocks);
		parseBlocks(blocks, true);

		setTimeout(() => {
			insertBlock(
				block,
				parentInnerBlocks.findIndex(getCurrentBlockPosition) + 1,
				firstParentClientId,
			);
			parseBlocks(blocks, false);
		}, 500);
	};

	const deleteBlock = () => {
		const blocks = wp.data.select("core/block-editor").getBlocks();
		parseBlocks(blocks, true);

		setTimeout(() => {
			selectBlock(clientId);
			wp.data.dispatch("core/block-editor").removeBlocks(clientId);
			parseBlocks(blocks, false);
		}, 500);
	};

	return (
		<>
			{attributes.showRepeaterToggle && (
				<BlockControls>
					<ToolbarGroup>
						<ToolbarButton
							describedBy={"Duplicate Block"}
							onClick={duplicateBlock}
							icon={copy}
						/>
						<ToolbarButton
							describedBy={"Delete Block"}
							onClick={deleteBlock}
							icon={trash}
						/>
					</ToolbarGroup>
				</BlockControls>
			)}

			<InspectorAdvancedControls>
				<ToggleControl
					label={__("Show Repeater Option", "wpdev")}
					checked={attributes.showRepeaterToggle}
					onChange={(showRepeaterToggle) =>
						setAttributes({ showRepeaterToggle })
					}
				/>
			</InspectorAdvancedControls>
		</>
	);
}

addFilter(
	"editor.BlockEdit",
	"wpdev/toggle-repeater",
	createHigherOrderComponent((BlockEdit) => {
		return (props) => {
			if (!ALLOWED_BLOCKS.includes(props.name)) {
				return <BlockEdit {...props} />;
			}

			return (
				<>
					<BlockEdit {...props} />
					<RepeaterToggleEdit {...props} />
				</>
			);
		};
	}),
);
