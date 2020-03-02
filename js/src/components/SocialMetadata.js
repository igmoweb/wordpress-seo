/* External dependencies */
import { createPortal, Fragment } from "@wordpress/element";
import { Collapsible } from "@yoast/components";

/* Internal dependencies */
import { FacebookViewContainer } from "../containers/FacebookView";

/**
 * Component that renders the social metadata collapsibles.
 *
 * @returns {React.Component} The social metadata collapsibles.
 */
const Social = () => {
	return createPortal(
		<Fragment>
			<Collapsible
				hasPadding={ true }
				hasSeparator={ true }
				title="Facebook"
			>
				<FacebookViewContainer />
			</Collapsible>
			<Collapsible
				hasPadding={ true }
				hasSeparator={ true }
				title="Twitter"
			>
				<p>twitter</p>
			</Collapsible>
		</Fragment>,
		document.getElementById( "wpseo-section-social" )
	);
};

export default Social;

