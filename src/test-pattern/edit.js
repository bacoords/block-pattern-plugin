/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from "@wordpress/i18n";

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps, InspectorControls } from "@wordpress/block-editor";

import {
	Placeholder,
	ToggleControl,
	TextControl,
	PanelBody,
	PanelRow,
} from "@wordpress/components";

import ServerSideRender from "@wordpress/server-side-render";

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import "./editor.scss";

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
	const blockProps = useBlockProps();
	return (
		<>
			<InspectorControls>
				<PanelBody title={__("Pattern Options")} initialOpen={true}>
					<PanelRow>
						<ToggleControl
							checked={attributes.showSinglePattern}
							label={__("Show A Single Pattern")}
							onChange={(showSinglePattern) => {
								setAttributes({
									showSinglePattern: showSinglePattern,
								});
							}}
						/>
					</PanelRow>
					{attributes.showSinglePattern && (
						<>
							<TextControl
								label={__("Pattern Name")}
								value={attributes.singlePatternName}
								onChange={(singlePatternName) => {
									setAttributes({
										singlePatternName: singlePatternName,
									});
								}}
							/>
						</>
					)}

					{!attributes.showSinglePattern && (
						<>
							<PanelRow>
								<ToggleControl
									checked={attributes.showPatternsInTheme}
									label={__("Show Theme Patterns")}
									onChange={(showPatternsInTheme) => {
										setAttributes({
											showPatternsInTheme: showPatternsInTheme,
										});
									}}
								/>
							</PanelRow>
							<PanelRow>
								<ToggleControl
									checked={attributes.showPatternsInDB}
									label={__("Show Stored Patterns")}
									onChange={(showPatternsInDB) => {
										setAttributes({
											showPatternsInDB: showPatternsInDB,
										});
									}}
								/>
							</PanelRow>
						</>
					)}
				</PanelBody>
			</InspectorControls>
			<div {...blockProps}>
				{/* <ServerSideRender block="wpdev/test-pattern" /> */}
				<Placeholder label="All Patterns"></Placeholder>
			</div>
		</>
	);
}
