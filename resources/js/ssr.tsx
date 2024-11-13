import ReactDOMServer from 'react-dom/server'
import { createInertiaApp } from '@inertiajs/react'
import createServer from '@inertiajs/react/server'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { route } from '../../vendor/tightenco/ziggy'
import { RouteName } from 'ziggy-js'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel'

createServer((page) =>
	createInertiaApp({
		page,
		render: ReactDOMServer.renderToString,
		title: (title) => `${title} - ${appName}`,
		resolve: (name) =>
			resolvePageComponent(
				`./Pages/${name}.tsx`,
				import.meta.glob('./Pages/**/*.tsx'),
			),
		setup: ({ App, props }) => {
			// @ts-expect-error - TODO: Unclear how to resolve error. Original boilerplate code was written in vanilla JS.
			global.route<RouteName> = (name, params, absolute) =>
				route(name, params as any, absolute, {
					// @ts-expect-error
					...page.props.ziggy,
					// @ts-expect-error
					location: new URL(page.props.ziggy.location),
				})

			return <App {...props} />
		},
	}),
)
