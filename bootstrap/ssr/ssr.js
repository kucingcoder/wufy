import "clsx";
import { buildSSRBody, config, createLayoutPropsStore, getInitialPageFromDOM, http, isPropsObject, isPropsObjectOrCallback, normalizeLayouts, router, setupProgress } from "@inertiajs/core";
import "laravel-precognition";
import server_default from "@inertiajs/core/server";
//#region \0rolldown/runtime.js
var __defProp = Object.defineProperty;
var __exportAll = (all, no_symbols) => {
	let target = {};
	for (var name in all) __defProp(target, name, {
		get: all[name],
		enumerable: true
	});
	if (!no_symbols) __defProp(target, Symbol.toStringTag, { value: "Module" });
	return target;
};
//#endregion
//#region node_modules/svelte/src/constants.js
var UNINITIALIZED = Symbol();
//#endregion
//#region node_modules/svelte/src/escaping.js
var ATTR_REGEX = /[&"<]/g;
var CONTENT_REGEX = /[&<]/g;
/**
* @template V
* @param {V} value
* @param {boolean} [is_attr]
*/
function escape_html(value, is_attr) {
	const str = String(value ?? "");
	const pattern = is_attr ? ATTR_REGEX : CONTENT_REGEX;
	pattern.lastIndex = 0;
	let escaped = "";
	let last = 0;
	while (pattern.test(str)) {
		const i = pattern.lastIndex - 1;
		const ch = str[i];
		escaped += str.substring(last, i) + (ch === "&" ? "&amp;" : ch === "\"" ? "&quot;" : "&lt;");
		last = i + 1;
	}
	return escaped + str.substring(last);
}
Array.isArray;
Array.prototype.indexOf;
Array.prototype.includes;
Array.from;
Array.prototype;
var has_own_property = Object.prototype.hasOwnProperty;
//#endregion
//#region node_modules/svelte/src/internal/shared/attributes.js
/**
* `<div translate={false}>` should be rendered as `<div translate="no">` and _not_
* `<div translate="false">`, which is equivalent to `<div translate="yes">`. There
* may be other odd cases that need to be added to this list in future
* @type {Record<string, Map<any, string>>}
*/
var replacements = { translate: new Map([[true, "yes"], [false, "no"]]) };
/**
* @template V
* @param {string} name
* @param {V} value
* @param {boolean} [is_boolean]
* @returns {string}
*/
function attr(name, value, is_boolean = false) {
	if (name === "hidden" && value !== "until-found") is_boolean = true;
	if (value == null || !value && is_boolean) return "";
	const normalized = has_own_property.call(replacements, name) && replacements[name].get(value) || value;
	return ` ${name}${is_boolean ? `=""` : `="${escape_html(normalized, true)}"`}`;
}
var whitespace = [..." 	\n\r\f\xA0\v﻿"];
/**
* @param {any} value
* @param {string | null} [hash]
* @param {Record<string, boolean>} [directives]
* @returns {string | null}
*/
function to_class(value, hash, directives) {
	var classname = value == null ? "" : "" + value;
	if (hash) classname = classname ? classname + " " + hash : hash;
	if (directives) {
		for (var key of Object.keys(directives)) if (directives[key]) classname = classname ? classname + " " + key : key;
		else if (classname.length) {
			var len = key.length;
			var a = 0;
			while ((a = classname.indexOf(key, a)) >= 0) {
				var b = a + len;
				if ((a === 0 || whitespace.includes(classname[a - 1])) && (b === classname.length || whitespace.includes(classname[b]))) classname = (a === 0 ? "" : classname.substring(0, a)) + classname.substring(b + 1);
				else a = b;
			}
		}
	}
	return classname === "" ? null : classname;
}
/**
*
* @param {Record<string,any>} styles
* @param {boolean} important
*/
function append_styles(styles, important = false) {
	var separator = important ? " !important;" : ";";
	var css = "";
	for (var key of Object.keys(styles)) {
		var value = styles[key];
		if (value != null && value !== "") css += " " + key + ": " + value + separator;
	}
	return css;
}
/**
* @param {string} name
* @returns {string}
*/
function to_css_name(name) {
	if (name[0] !== "-" || name[1] !== "-") return name.toLowerCase();
	return name;
}
/**
* @param {any} value
* @param {Record<string, any> | [Record<string, any>, Record<string, any>]} [styles]
* @returns {string | null}
*/
function to_style(value, styles) {
	if (styles) {
		var new_style = "";
		/** @type {Record<string,any> | undefined} */
		var normal_styles;
		/** @type {Record<string,any> | undefined} */
		var important_styles;
		if (Array.isArray(styles)) {
			normal_styles = styles[0];
			important_styles = styles[1];
		} else normal_styles = styles;
		if (value) {
			value = String(value).replaceAll(/\s*\/\*.*?\*\/\s*/g, "").trim();
			/** @type {boolean | '"' | "'"} */
			var in_str = false;
			var in_apo = 0;
			var in_comment = false;
			var reserved_names = [];
			if (normal_styles) reserved_names.push(...Object.keys(normal_styles).map(to_css_name));
			if (important_styles) reserved_names.push(...Object.keys(important_styles).map(to_css_name));
			var start_index = 0;
			var name_index = -1;
			const len = value.length;
			for (var i = 0; i < len; i++) {
				var c = value[i];
				if (in_comment) {
					if (c === "/" && value[i - 1] === "*") in_comment = false;
				} else if (in_str) {
					if (in_str === c) in_str = false;
				} else if (c === "/" && value[i + 1] === "*") in_comment = true;
				else if (c === "\"" || c === "'") in_str = c;
				else if (c === "(") in_apo++;
				else if (c === ")") in_apo--;
				if (!in_comment && in_str === false && in_apo === 0) {
					if (c === ":" && name_index === -1) name_index = i;
					else if (c === ";" || i === len - 1) {
						if (name_index !== -1) {
							var name = to_css_name(value.substring(start_index, name_index).trim());
							if (!reserved_names.includes(name)) {
								if (c !== ";") i++;
								var property = value.substring(start_index, i).trim();
								new_style += " " + property + ";";
							}
						}
						start_index = i + 1;
						name_index = -1;
					}
				}
			}
		}
		if (normal_styles) new_style += append_styles(normal_styles);
		if (important_styles) new_style += append_styles(important_styles, true);
		new_style = new_style.trim();
		return new_style === "" ? null : new_style;
	}
	return value == null ? null : String(value);
}
//#endregion
//#region node_modules/svelte/src/internal/client/constants.js
var CLEAN = 1024;
var DIRTY = 2048;
var MAYBE_DIRTY = 4096;
/**
* 'Transparent' effects do not create a transition boundary.
* This is on a block effect 99% of the time but may also be on a branch effect if its parent block effect was pruned
*/
var EFFECT_TRANSPARENT = 65536;
var EFFECT_PRESERVED = 1 << 19;
new class StaleReactionError extends Error {
	name = "StaleReactionError";
	message = "The reaction that called `getAbortSignal()` was re-run or destroyed";
}();
globalThis.document?.contentType;
//#endregion
//#region node_modules/svelte/src/internal/shared/errors.js
/**
* `%name%(...)` can only be used during component initialisation
* @param {string} name
* @returns {never}
*/
function lifecycle_outside_component(name) {
	throw new Error(`https://svelte.dev/e/lifecycle_outside_component`);
}
/**
* Context was not set in a parent component
* @returns {never}
*/
function missing_context() {
	throw new Error(`https://svelte.dev/e/missing_context`);
}
~(DIRTY | MAYBE_DIRTY | CLEAN);
EFFECT_TRANSPARENT | EFFECT_PRESERVED;
var EMPTY_COMMENT = `<!---->`;
//#endregion
//#region node_modules/svelte/src/internal/server/errors.js
/**
* `%name%(...)` is not available on the server
* @param {string} name
* @returns {never}
*/
function lifecycle_function_unavailable(name) {
	const error = /* @__PURE__ */ new Error(`lifecycle_function_unavailable\n\`${name}(...)\` is not available on the server\nhttps://svelte.dev/e/lifecycle_function_unavailable`);
	error.name = "Svelte error";
	throw error;
}
//#endregion
//#region node_modules/svelte/src/internal/server/context.js
/** @type {SSRContext | null} */
var ssr_context = null;
/**
* @template T
* @returns {[() => T, (context: T) => T]}
* @since 5.40.0
*/
function createContext() {
	const key = {};
	return [() => {
		if (!hasContext(key)) missing_context();
		return getContext(key);
	}, (context) => setContext(key, context)];
}
/**
* @template T
* @param {any} key
* @returns {T}
*/
function getContext(key) {
	return get_or_init_context_map("getContext").get(key);
}
/**
* @template T
* @param {any} key
* @param {T} context
* @returns {T}
*/
function setContext(key, context) {
	get_or_init_context_map("setContext").set(key, context);
	return context;
}
/**
* @param {any} key
* @returns {boolean}
*/
function hasContext(key) {
	return get_or_init_context_map("hasContext").has(key);
}
/**
* @param {string} name
* @returns {Map<unknown, unknown>}
*/
function get_or_init_context_map(name) {
	if (ssr_context === null) lifecycle_outside_component(name);
	return ssr_context.c ??= new Map(get_parent_context(ssr_context) || void 0);
}
/**
* @param {SSRContext} ssr_context
* @returns {Map<unknown, unknown> | null}
*/
function get_parent_context(ssr_context) {
	let parent = ssr_context.p;
	while (parent !== null) {
		const context_map = parent.c;
		if (context_map !== null) return context_map;
		parent = parent.p;
	}
	return null;
}
//#endregion
//#region node_modules/svelte/src/internal/server/blocks/html.js
/**
* @param {string} value
*/
function html(value) {
	return "<!---->" + String(value ?? "") + "<!---->";
}
//#endregion
//#region node_modules/svelte/src/internal/server/index.js
/**
* @param {string} hash
* @param {Renderer} renderer
* @param {(renderer: Renderer) => Promise<void> | void} fn
* @returns {void}
*/
function head(hash, renderer, fn) {
	renderer.head((renderer) => {
		renderer.push(`<!--${hash}-->`);
		renderer.child(fn);
		renderer.push(EMPTY_COMMENT);
	});
}
/**
* @param {Record<string, unknown>[]} props
* @returns {Record<string, unknown>}
*/
function spread_props(props) {
	/** @type {Record<string, unknown>} */
	const merged_props = {};
	let key;
	for (let i = 0; i < props.length; i++) {
		const obj = props[i];
		if (obj == null) continue;
		for (key of Object.keys(obj)) {
			const desc = Object.getOwnPropertyDescriptor(obj, key);
			if (desc) Object.defineProperty(merged_props, key, desc);
			else merged_props[key] = obj[key];
		}
	}
	return merged_props;
}
/**
* @param {unknown} value
* @returns {string}
*/
function stringify(value) {
	return typeof value === "string" ? value : value == null ? "" : value + "";
}
/**
* @param {any} value
* @param {string | undefined} [hash]
* @param {Record<string, boolean>} [directives]
*/
function attr_class(value, hash, directives) {
	var result = to_class(value, hash, directives);
	return result ? ` class="${escape_html(result, true)}"` : "";
}
/**
* @param {any} value
* @param {Record<string,any>|[Record<string,any>,Record<string,any>]} [directives]
*/
function attr_style(value, directives) {
	var result = to_style(value, directives);
	return result ? ` style="${escape_html(result, true)}"` : "";
}
/** @param {any} array_like_or_iterator */
function ensure_array_like(array_like_or_iterator) {
	if (array_like_or_iterator) return array_like_or_iterator.length !== void 0 ? array_like_or_iterator : Array.from(array_like_or_iterator);
	return [];
}
/**
* @template V
* @param {() => V} get_value
*/
function once(get_value) {
	let value = UNINITIALIZED;
	return () => {
		if (value === UNINITIALIZED) value = get_value();
		return value;
	};
}
/**
* @template T
* @param {()=>T} fn
* @returns {(new_value?: T) => (T | void)}
*/
function derived(fn) {
	const get_value = ssr_context === null ? fn : once(fn);
	/** @type {T | undefined} */
	let updated_value;
	return function(new_value) {
		if (arguments.length === 0) return updated_value ?? get_value();
		updated_value = new_value;
		return updated_value;
	};
}
//#endregion
//#region resources/js/Pages/Error.svelte
var Error_exports = /* @__PURE__ */ __exportAll({ default: () => Error$1 });
function Error$1($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		let { status } = $$props;
		let title = derived(() => ({
			503: "503: Service Unavailable",
			500: "500: Server Error",
			404: "404: Page Not Found",
			403: "403: Forbidden"
		})[status] || "Error");
		let description = derived(() => ({
			503: "Sorry, we are doing some maintenance. Please check back soon.",
			500: "Whoops, something went wrong on our servers.",
			404: "Sorry, the page you are looking for could not be found.",
			403: "Sorry, you are forbidden from accessing this page."
		})[status] || "An unexpected error has occurred.");
		head("7s8opz", $$renderer, ($$renderer) => {
			$$renderer.title(($$renderer) => {
				$$renderer.push(`<title>${escape_html(title())} | Wufy</title>`);
			});
			$$renderer.push(`<link rel="icon" type="image/webp" href="/icon.webp"/> <link rel="preconnect" href="https://fonts.googleapis.com"/> <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin=""/> <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&amp;display=swap" rel="stylesheet"/>`);
		});
		$$renderer.push(`<div class="bg-[#020617] text-slate-100 font-['Plus_Jakarta_Sans'] selection:bg-sky-500/30 overflow-hidden min-h-screen flex items-center justify-center p-6 relative"><div class="fixed inset-0 pointer-events-none z-0"><div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,#0f172a_0%,#020617_100%)]"></div> <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-sky-600/10 blur-[120px] rounded-full"></div></div> <div class="relative z-10 text-center max-w-2xl"><div><h1 class="text-8xl lg:text-[12rem] font-black tracking-tighter text-white opacity-10 mb-[-2rem] lg:mb-[-4rem]">${escape_html(status)}</h1> <div class="relative inline-block mb-12"><div class="absolute -inset-4 bg-sky-500/20 blur-xl rounded-full opacity-50 animate-pulse"></div> <div class="relative w-24 h-24 rounded-3xl bg-slate-900 border border-slate-800 flex items-center justify-center shadow-2xl"><svg class="w-12 h-12 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.268 16c-.77 1.333.192 3 1.732 3z"></path></svg></div></div> <h2 class="text-4xl lg:text-6xl font-black text-white mb-6 tracking-tight">${escape_html(title().split(": ")[1])}</h2> <p class="text-slate-400 text-lg lg:text-xl font-light mb-12 leading-relaxed">${escape_html(description())}</p> <a href="/" class="inline-flex items-center gap-3 px-8 py-4 rounded-2xl bg-white text-slate-950 font-bold hover:bg-sky-500 hover:text-white transition-all shadow-xl hover:shadow-sky-500/20 active:scale-95 group"><svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg> Kembali ke Beranda</a></div></div></div>`);
	});
}
//#endregion
//#region node_modules/svelte/src/index-server.js
function mount() {
	lifecycle_function_unavailable("mount");
}
function hydrate() {
	lifecycle_function_unavailable("hydrate");
}
//#endregion
//#region resources/js/Pages/ProjectDetail.svelte
var ProjectDetail_exports = /* @__PURE__ */ __exportAll({ default: () => ProjectDetail });
function ProjectDetail($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		let { project } = $$props;
		head("1l5pzj5", $$renderer, ($$renderer) => {
			$$renderer.title(($$renderer) => {
				$$renderer.push(`<title>${escape_html(project.title)} - Case Study</title>`);
			});
			$$renderer.push(`<meta name="description"${attr("content", project.short_description)}/> <meta name="keywords"${attr("content", `${stringify(project.title.split(" ").join(", "))}, portfolio, case study, digital solutions`)}/> <meta property="og:type" content="article"/> <meta property="og:url"${attr("content", `https://wufy.test/project/${stringify(project.slug)}`)}/> <meta property="og:title"${attr("content", `${stringify(project.title)} - Case Study`)}/> <meta property="og:description"${attr("content", project.short_description)}/> <meta property="og:image"${attr("content", project.thumbnail ? `/storage/${project.thumbnail}` : "/icon.webp")}/> <meta property="twitter:card" content="summary_large_image"/> <meta property="twitter:url"${attr("content", `https://wufy.test/project/${stringify(project.slug)}`)}/> <meta property="twitter:title"${attr("content", `${stringify(project.title)} - Case Study`)}/> <meta property="twitter:description"${attr("content", project.short_description)}/> <meta property="twitter:image"${attr("content", project.thumbnail ? `/storage/${project.thumbnail}` : "/icon.webp")}/> <link rel="icon" type="image/webp" href="/icon.webp"/> <link rel="preconnect" href="https://fonts.googleapis.com"/> <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin=""/> <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&amp;family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&amp;display=swap" rel="stylesheet"/> `);
			$$renderer.push(`<script type="application/ld+json">
        {JSON.stringify({
            "@context": "https://schema.org",
            "@type": "Article",
            "headline": project.title,
            "description": project.short_description,
            "image": project.thumbnail ? \`https://wufy.test/storage/\${project.thumbnail}\` : "https://wufy.test/icon.webp",
            "url": \`https://wufy.test/project/\${project.slug}\`,
            "datePublished": project.created_at,
            "author": {
                "@type": "Person",
                "name": "Admin Wufy"
            }
        })}
    <\/script>`);
		});
		$$renderer.push(`<div class="bg-[#020617] text-slate-100 font-['Plus_Jakarta_Sans'] selection:bg-sky-500/30 min-h-screen overflow-x-hidden"><div class="fixed inset-0 pointer-events-none z-0"><div class="absolute top-0 right-0 w-[500px] h-[500px] bg-sky-600/10 blur-[120px] rounded-full"></div></div> <header class="fixed top-0 left-0 right-0 z-50 bg-slate-950/50 backdrop-blur-md border-b border-slate-800/50"><div class="container mx-auto px-6 h-20 flex items-center justify-between"><a href="/" class="flex items-center gap-2 text-slate-400 hover:text-sky-400 transition-colors group"><div class="w-8 h-8 rounded-lg bg-slate-900 border border-slate-800 flex items-center justify-center group-hover:border-sky-500/50 transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></div> <span class="font-bold uppercase tracking-widest text-xs">Back to Home</span></a></div></header> <main class="pt-32 pb-40 px-6 relative z-10"><article class="container mx-auto max-w-5xl"><div class="mb-20 text-left"><h1 class="text-5xl lg:text-8xl font-black tracking-tighter mb-4 leading-none text-left">${escape_html(project.title)}</h1> <div class="flex flex-wrap items-center gap-6"><div class="flex items-center gap-2 text-sky-500 font-black uppercase tracking-[0.3em] text-sm"><span>${escape_html(project.month || "Januari")}</span> <span class="w-2 h-2 rounded-full bg-slate-800"></span> <span>${escape_html(project.year || "2024")}</span></div> `);
		if (project.is_opensource && project.github_link) {
			$$renderer.push("<!--[0-->");
			$$renderer.push(`<a${attr("href", project.github_link)} target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 hover:text-white hover:border-sky-500/50 transition-all group shadow-xl"><svg class="w-5 h-5 text-sky-500 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"></path></svg> <span class="text-xs font-bold uppercase tracking-wider">View Source</span></a>`);
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></div></div> <div class="relative rounded-[3rem] overflow-hidden bg-slate-900 border border-slate-800 mb-20 shadow-2xl group">`);
		if (project.thumbnail) {
			$$renderer.push("<!--[0-->");
			$$renderer.push(`<img${attr("src", `/storage/${stringify(project.thumbnail)}`)}${attr("alt", project.title)} class="w-full h-auto object-cover"/>`);
		} else {
			$$renderer.push("<!--[-1-->");
			$$renderer.push(`<div class="aspect-video bg-gradient-to-br from-slate-800 to-slate-950 flex items-center justify-center text-9xl opacity-20">🖼️</div>`);
		}
		$$renderer.push(`<!--]--></div> <div class="max-w-4xl mx-auto"><div class="prose prose-invert prose-sky max-w-none svelte-1l5pzj5"><div class="text-slate-300 leading-relaxed text-lg space-y-8">`);
		if (project.blog_content) {
			$$renderer.push("<!--[0-->");
			$$renderer.push(`${html(project.blog_content)}`);
		} else {
			$$renderer.push("<!--[-1-->");
			$$renderer.push(`<p>No detailed description available for this project</p>`);
		}
		$$renderer.push(`<!--]--></div></div></div> `);
		if (project.galleries && project.galleries.length > 0) {
			$$renderer.push("<!--[0-->");
			$$renderer.push(`<div class="mt-40"><h2 class="text-4xl lg:text-6xl font-black mb-16 tracking-tight">Project <span class="text-sky-500">Gallery</span></h2> <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"><!--[-->`);
			const each_array = ensure_array_like(project.galleries);
			for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
				let gallery = each_array[$$index];
				$$renderer.push(`<button class="group relative rounded-2xl overflow-hidden bg-slate-900 border border-slate-800 text-left outline-none"><img${attr("src", `/storage/${stringify(gallery.image_path)}`)}${attr("alt", gallery.title || project.title)} class="w-full h-auto hover:scale-105 transition-transform duration-700"/> `);
				if (gallery.title) {
					$$renderer.push("<!--[0-->");
					$$renderer.push(`<div class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-slate-950/90 via-slate-950/40 to-transparent"><span class="text-sm font-bold text-white tracking-tight">${escape_html(gallery.title)}</span></div>`);
				} else $$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]--> <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-sky-500/10 pointer-events-none"><div class="w-12 h-12 rounded-full bg-sky-500 text-white flex items-center justify-center shadow-lg"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg></div></div></button>`);
			}
			$$renderer.push(`<!--]--></div></div>`);
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></article></main> `);
		$$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></div>`);
	});
}
//#endregion
//#region resources/js/Pages/Welcome.svelte
var Welcome_exports = /* @__PURE__ */ __exportAll({ default: () => Welcome });
function Welcome($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		let { profile, education = [], experiences = [], projects = [], skills = [], services = [], visionMission, certificates = [] } = $$props;
		let activeSection = "home";
		let searchQuery = "";
		let currentPage = 1;
		const itemsPerPage = 6;
		let filteredProjects = derived(() => projects.filter((p) => p.title.toLowerCase().includes(searchQuery.toLowerCase()) || p.short_description.toLowerCase().includes(searchQuery.toLowerCase())));
		let paginatedProjects = derived(() => filteredProjects().slice((currentPage - 1) * itemsPerPage, currentPage * itemsPerPage));
		let totalPages = derived(() => Math.ceil(filteredProjects().length / itemsPerPage));
		let formattedPhone = derived(() => (profile?.phone || "").replace(/[^0-9]/g, "").replace(/^0/, "62"));
		const categorizedSkills = derived(() => skills.reduce((acc, skill) => {
			const cat = skill.category || "teknologi";
			if (!acc[cat]) acc[cat] = [];
			acc[cat].push(skill);
			return acc;
		}, {}));
		const getCategoryLabel = (cat) => {
			return {
				"bahasa": "Bahasa",
				"teknologi": "Teknologi & Alat",
				"minat": "Minat & Hobi"
			}[cat] || cat;
		};
		const icons = {
			linkedin: "<svg class=\"w-5 h-5\" fill=\"currentColor\" viewBox=\"0 0 24 24\"><path d=\"M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z\"/></svg>",
			github: "<svg class=\"w-5 h-5\" fill=\"currentColor\" viewBox=\"0 0 24 24\"><path d=\"M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z\"/></svg>",
			instagram: "<svg class=\"w-5 h-5\" fill=\"currentColor\" viewBox=\"0 0 24 24\"><path d=\"M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z\"/></svg>",
			default: "<svg class=\"w-5 h-5\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1\"></path></svg>"
		};
		const getIcon = (title) => {
			const lower = title.toLowerCase();
			if (lower.includes("linkedin")) return icons.linkedin;
			if (lower.includes("github")) return icons.github;
			if (lower.includes("instagram")) return icons.instagram;
			return icons.default;
		};
		const getServiceIcon = (iconName) => {
			const name = iconName?.toLowerCase() || "";
			if (name.includes("desktop") || name.includes("computer")) return "<svg class=\"w-8 h-8\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z\"></path></svg>";
			if (name.includes("mobile") || name.includes("phone")) return "<svg class=\"w-8 h-8\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z\"></path></svg>";
			if (name.includes("paint") || name.includes("design")) return "<svg class=\"w-8 h-8\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01\"></path></svg>";
			if (name.includes("code") || name.includes("dev")) return "<svg class=\"w-8 h-8\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4\"></path></svg>";
			if (name.includes("server")) return "<svg class=\"w-8 h-8\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01\"></path></svg>";
			return "<svg class=\"w-8 h-8\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M13 10V3L4 14h7v7l9-11h-7z\"></path></svg>";
		};
		head("178l0i5", $$renderer, ($$renderer) => {
			$$renderer.title(($$renderer) => {
				$$renderer.push(`<title>${escape_html(profile?.full_name || "Portfolio")} - ${escape_html(profile?.job_title || "Expert")}</title>`);
			});
			$$renderer.push(`<meta name="description"${attr("content", profile?.description || "Portofolio profesional yang menampilkan proyek inovatif dan solusi digital kreatif.")} class="svelte-178l0i5"/> <meta name="keywords"${attr("content", `${stringify(skills?.map((s) => s.name).join(", "))}, ${stringify(services?.map((s) => s.title).join(", "))}, portfolio, digital solutions, ${stringify(profile?.full_name)}`)} class="svelte-178l0i5"/> <meta name="author"${attr("content", profile?.full_name)} class="svelte-178l0i5"/> <link rel="icon" type="image/webp" href="/icon.webp" class="svelte-178l0i5"/> <meta property="og:type" content="website" class="svelte-178l0i5"/> <meta property="og:url" content="https://wufy.test/" class="svelte-178l0i5"/> <meta property="og:title"${attr("content", `${stringify(profile?.full_name)} - ${stringify(profile?.job_title)}`)} class="svelte-178l0i5"/> <meta property="og:description"${attr("content", profile?.description)} class="svelte-178l0i5"/> <meta property="og:image"${attr("content", profile?.avatar ? `/storage/${profile.avatar}` : "/icon.webp")} class="svelte-178l0i5"/> <meta property="twitter:card" content="summary_large_image" class="svelte-178l0i5"/> <meta property="twitter:url" content="https://wufy.test/" class="svelte-178l0i5"/> <meta property="twitter:title"${attr("content", `${stringify(profile?.full_name)} - ${stringify(profile?.job_title)}`)} class="svelte-178l0i5"/> <meta property="twitter:description"${attr("content", profile?.description)} class="svelte-178l0i5"/> <meta property="twitter:image"${attr("content", profile?.avatar ? `/storage/${profile.avatar}` : "/icon.webp")} class="svelte-178l0i5"/> <link rel="preconnect" href="https://fonts.googleapis.com" class="svelte-178l0i5"/> <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="" class="svelte-178l0i5"/> <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&amp;family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&amp;display=swap" rel="stylesheet" class="svelte-178l0i5"/> `);
			$$renderer.push(`<script type="application/ld+json" class="svelte-178l0i5">
        {JSON.stringify({
            "@context": "https://schema.org",
            "@type": "Person",
            "name": profile?.full_name,
            "jobTitle": profile?.job_title,
            "url": "https://wufy.test/",
            "image": profile?.avatar ? \`https://wufy.test/storage/\${profile.avatar}\` : "https://wufy.test/icon.webp",
            "sameAs": profile?.links?.map(l => l.link) || [],
            "description": profile?.description,
            "address": {
                "@type": "PostalAddress",
                "addressLocality": profile?.city,
                "addressRegion": profile?.province,
                "addressCountry": "ID"
            }
        })}
    <\/script>`);
		});
		{
			$$renderer.push("<!--[0-->");
			$$renderer.push(`<div class="fixed inset-0 z-[100] bg-[#020617] flex flex-col items-center justify-center overflow-hidden svelte-178l0i5"><div class="absolute inset-0 opacity-20 pointer-events-none svelte-178l0i5"><div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-sky-500/10 rounded-full blur-[120px] animate-pulse svelte-178l0i5"></div> <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgdmlld0JveD0iMCAwIDQwIDQwIj48ZyBmaWxsPSJub25lIiBzdHJva2U9InJnYmEoMTQ4LCAxNjMsIDE4NCwgMC4wNSkiIHN0cm9rZS13aWR0aD0iMC41Ij48cGF0aCBkPSJNMCA0MGg0MFYwSDB6Ii8+PC9nPjwvc3ZnPg==')] bg-repeat opacity-20 svelte-178l0i5"></div></div> <div class="relative flex flex-col items-center svelte-178l0i5"><div class="relative w-32 h-32 mb-12 svelte-178l0i5"><div class="absolute inset-0 border-2 border-sky-500/10 rounded-full svelte-178l0i5"></div> <div class="absolute inset-0 border-t-2 border-sky-500 rounded-full animate-spin svelte-178l0i5"></div> <div class="absolute inset-4 border-b-2 border-indigo-500 rounded-full animate-spin-reverse opacity-50 svelte-178l0i5"></div> <div class="absolute inset-10 bg-gradient-to-br from-sky-400 to-indigo-600 rounded-full shadow-[0_0_30px_rgba(14,165,233,0.5)] animate-pulse flex items-center justify-center svelte-178l0i5"><div class="w-2 h-2 bg-white rounded-full svelte-178l0i5"></div></div></div> <div class="text-center relative svelte-178l0i5"><div class="text-sky-500 font-mono text-xs tracking-[0.5em] mb-3 uppercase animate-pulse svelte-178l0i5">System Initializing</div> <div class="h-1 w-48 bg-slate-800 rounded-full overflow-hidden relative svelte-178l0i5"><div class="absolute inset-y-0 left-0 bg-sky-500 rounded-full animate-progress-loading svelte-178l0i5"></div></div> <div class="absolute -inset-x-20 -inset-y-10 border border-sky-500/5 rounded-3xl pointer-events-none svelte-178l0i5"><div class="absolute inset-x-0 h-[1px] bg-sky-500/20 top-0 animate-scan svelte-178l0i5"></div></div></div> <div class="mt-8 flex gap-3 svelte-178l0i5"><!--[-->`);
			const each_array = ensure_array_like(Array(3));
			for (let i = 0, $$length = each_array.length; i < $$length; i++) {
				each_array[i];
				$$renderer.push(`<div class="w-1.5 h-1.5 bg-sky-500/30 rounded-full animate-bounce svelte-178l0i5"${attr_style(`animation-delay: ${stringify(i * .2)}s`)}></div>`);
			}
			$$renderer.push(`<!--]--></div></div> <div class="absolute bottom-12 left-1/2 -translate-x-1/2 flex items-center gap-8 text-[10px] font-mono text-slate-600 tracking-widest uppercase svelte-178l0i5"><span class="svelte-178l0i5">Core: Active</span> <span class="w-1 h-1 bg-slate-800 rounded-full svelte-178l0i5"></span> <span class="svelte-178l0i5">Uplink: Secure</span> <span class="w-1 h-1 bg-slate-800 rounded-full svelte-178l0i5"></span> <span class="svelte-178l0i5">Protocols: Ready</span></div></div>`);
		}
		$$renderer.push(`<!--]--> <div class="bg-[#020617] text-slate-100 font-['Plus_Jakarta_Sans'] selection:bg-sky-500/30 overflow-x-hidden min-h-screen svelte-178l0i5"><div class="fixed inset-0 pointer-events-none z-0 overflow-hidden svelte-178l0i5"><div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,#0f172a_0%,#020617_100%)] svelte-178l0i5"></div> <div class="absolute top-[-10%] left-[-10%] w-[600px] h-[600px] bg-sky-600/15 blur-[120px] rounded-full animate-blob svelte-178l0i5"></div> <div class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-indigo-600/10 blur-[120px] rounded-full animate-blob animation-delay-2000 svelte-178l0i5"></div> <div class="absolute top-[20%] right-[10%] w-[400px] h-[400px] bg-blue-500/10 blur-[100px] rounded-full animate-blob animation-delay-4000 svelte-178l0i5"></div> <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgdmlld0JveD0iMCAwIDQwIDQwIj48ZyBmaWxsPSJub25lIiBzdHJva2U9InJnYmEoMTQ4LCAxNjMsIDE4NCwgMC4wNSkiIHN0cm9rZS13aWR0aD0iMC41Ij48cGF0aCBkPSJNMCA0MGg0MFYwSDB6Ii8+PC9nPjwvc3ZnPg==')] bg-repeat opacity-40 svelte-178l0i5"></div> <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,transparent_0%,#020617_80%)] opacity-50 svelte-178l0i5"></div></div> <nav class="fixed top-6 left-1/2 -translate-x-1/2 z-50 hidden md:flex justify-center pointer-events-none svelte-178l0i5"><div class="bg-slate-900/40 backdrop-blur-md border border-slate-800/50 px-4 py-2 rounded-full flex items-center gap-1 shadow-2xl pointer-events-auto svelte-178l0i5"><button${attr_class(`px-4 py-2 text-xs font-bold tracking-widest transition-all rounded-full ${stringify(activeSection === "home" ? "bg-sky-500 text-white shadow-lg shadow-sky-500/20" : "text-slate-400 hover:text-white hover:bg-white/5")}`, "svelte-178l0i5")}>beranda</button> <button${attr_class(`px-4 py-2 text-xs font-bold tracking-widest transition-all rounded-full ${stringify(activeSection === "vision" ? "bg-sky-500 text-white shadow-lg shadow-sky-500/20" : "text-slate-400 hover:text-white hover:bg-white/5")}`, "svelte-178l0i5")}>Visi/Misi</button> <button${attr_class(`px-4 py-2 text-xs font-bold tracking-widest transition-all rounded-full ${stringify(activeSection === "services" ? "bg-sky-500 text-white shadow-lg shadow-sky-500/20" : "text-slate-400 hover:text-white hover:bg-white/5")}`, "svelte-178l0i5")}>Jasa</button> <button${attr_class(`px-4 py-2 text-xs font-bold tracking-widest transition-all rounded-full ${stringify(activeSection === "projects" ? "bg-sky-500 text-white shadow-lg shadow-sky-500/20" : "text-slate-400 hover:text-white hover:bg-white/5")}`, "svelte-178l0i5")}>Projek</button> <button${attr_class(`px-4 py-2 text-xs font-bold tracking-widest transition-all rounded-full ${stringify(activeSection === "skills" ? "bg-sky-500 text-white shadow-lg shadow-sky-500/20" : "text-slate-400 hover:text-white hover:bg-white/5")}`, "svelte-178l0i5")}>Keahlian</button> <button${attr_class(`px-4 py-2 text-xs font-bold tracking-widest transition-all rounded-full ${stringify(activeSection === "experience" ? "bg-sky-500 text-white shadow-lg shadow-sky-500/20" : "text-slate-400 hover:text-white hover:bg-white/5")}`, "svelte-178l0i5")}>Pengalaman</button> <button${attr_class(`px-4 py-2 text-xs font-bold tracking-widest transition-all rounded-full ${stringify(activeSection === "education" ? "bg-sky-500 text-white shadow-lg shadow-sky-500/20" : "text-slate-400 hover:text-white hover:bg-white/5")}`, "svelte-178l0i5")}>Edukasi</button> <button${attr_class(`px-4 py-2 text-xs font-bold tracking-widest transition-all rounded-full ${stringify(activeSection === "certificates" ? "bg-sky-500 text-white shadow-lg shadow-sky-500/20" : "text-slate-400 hover:text-white hover:bg-white/5")}`, "svelte-178l0i5")}>Sertifikat</button> <button${attr_class(`px-4 py-2 text-xs font-bold tracking-widest transition-all rounded-full ${stringify(activeSection === "contact" ? "bg-sky-500 text-white shadow-lg shadow-sky-500/20" : "text-slate-400 hover:text-white hover:bg-white/5")}`, "svelte-178l0i5")}>Kontak</button></div></nav> <div class="fixed top-6 right-6 z-[60] md:hidden svelte-178l0i5"><button class="w-9 h-9 flex flex-col items-center justify-center gap-1 bg-slate-900/90 backdrop-blur-md border border-slate-800 rounded-lg shadow-2xl transition-all active:scale-95 svelte-178l0i5"${attr("aria-label", "Buka Menu")}><span${attr_class(`w-4 h-0.5 bg-white transition-all duration-300 ${stringify("")}`, "svelte-178l0i5")}></span> <span${attr_class(`w-4 h-0.5 bg-white transition-all duration-300 ${stringify("")}`, "svelte-178l0i5")}></span> <span${attr_class(`w-4 h-0.5 bg-white transition-all duration-300 ${stringify("")}`, "svelte-178l0i5")}></span></button></div> `);
		$$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--> <section id="home" class="relative min-h-screen flex items-center justify-center pt-20 px-6 lg:px-24 svelte-178l0i5"><div class="container mx-auto grid lg:grid-cols-[1.8fr_1fr] gap-16 items-center svelte-178l0i5"><div class="order-1 lg:order-2 flex flex-col items-center lg:items-end gap-8 svelte-178l0i5"><div class="relative group reveal svelte-178l0i5"><div class="absolute -inset-8 bg-sky-500/10 blur-[100px] rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-700 svelte-178l0i5"></div> <div class="relative w-64 h-64 lg:w-80 lg:h-80 bg-slate-900 border border-slate-800 p-3 rounded-3xl group-hover:border-sky-500/30 transition-all duration-500 shadow-2xl svelte-178l0i5"><div class="absolute inset-0 bg-sky-500/5 opacity-0 group-hover:opacity-100 transition-opacity svelte-178l0i5"></div> <div class="relative w-full h-full overflow-hidden rounded-2xl bg-slate-950 svelte-178l0i5">`);
		if (profile?.avatar) {
			$$renderer.push("<!--[0-->");
			$$renderer.push(`<img${attr("src", `/storage/${stringify(profile.avatar)}`)}${attr("alt", profile.full_name)} class="w-full h-full object-cover group-hover:scale-105 transition-all duration-700 svelte-178l0i5"/>`);
		} else {
			$$renderer.push("<!--[-1-->");
			$$renderer.push(`<div class="w-full h-full flex items-center justify-center text-6xl opacity-20 svelte-178l0i5">👤</div>`);
		}
		$$renderer.push(`<!--]--></div></div> <div class="absolute -bottom-8 left-1/2 -translate-x-1/2 flex gap-1.5 svelte-178l0i5"><div class="w-1.5 h-1.5 bg-sky-500 rounded-full animate-pulse svelte-178l0i5"></div> <div class="w-1.5 h-1.5 bg-sky-500/50 rounded-full animate-pulse delay-75 svelte-178l0i5"></div> <div class="w-1.5 h-1.5 bg-sky-500/30 rounded-full animate-pulse delay-150 svelte-178l0i5"></div></div></div> <div class="flex gap-4 reveal svelte-178l0i5">`);
		if (profile?.links) {
			$$renderer.push("<!--[0-->");
			$$renderer.push(`<!--[-->`);
			const each_array_2 = ensure_array_like(profile.links);
			for (let $$index_2 = 0, $$length = each_array_2.length; $$index_2 < $$length; $$index_2++) {
				let link = each_array_2[$$index_2];
				$$renderer.push(`<a${attr("href", link.link)} target="_blank" rel="noopener noreferrer"${attr("aria-label", `Kunjungi ${stringify(link.title)} ${stringify(profile.full_name)}`)} class="relative w-14 h-14 bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-400 hover:text-sky-400 hover:border-sky-500/50 transition-all clip-hexagon group/social overflow-hidden shadow-2xl svelte-178l0i5"><div class="absolute inset-0 bg-sky-500/5 opacity-0 group-hover/social:opacity-100 transition-opacity svelte-178l0i5"></div> ${html(getIcon(link.title))}</a>`);
			}
			$$renderer.push(`<!--]-->`);
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></div></div> <div class="order-2 lg:order-1 text-center lg:text-left svelte-178l0i5"><h1 class="text-4xl lg:text-6xl font-black tracking-tighter leading-[0.9] mb-6 text-white reveal svelte-178l0i5">${escape_html(profile?.full_name || "Your Name")}</h1> <h2 class="text-2xl lg:text-3xl font-medium text-slate-400 mb-8 font-['Space_Grotesk'] reveal svelte-178l0i5">${escape_html(profile?.job_title || "Gelar Profesional")}</h2> <p class="text-lg lg:text-xl text-slate-400 mb-2 leading-relaxed font-light reveal svelte-178l0i5">${escape_html(profile?.description || "Membangun solusi digital inovatif dengan fokus pada teknologi modern dan pengalaman pengguna yang luar biasa.")}</p> <div class="flex flex-col gap-6 items-center lg:items-start reveal svelte-178l0i5"><div class="flex flex-wrap items-center gap-x-8 gap-y-4 justify-center lg:justify-start svelte-178l0i5"><div class="flex items-center gap-2 text-slate-200 svelte-178l0i5"><svg class="w-4 h-4 text-sky-500 svelte-178l0i5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" class="svelte-178l0i5"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" class="svelte-178l0i5"></path></svg> <span class="font-medium svelte-178l0i5">${escape_html(profile?.city)}, ${escape_html(profile?.province)}</span></div> <div class="h-8 w-[1px] bg-slate-800 hidden sm:block svelte-178l0i5"></div> <div class="flex items-center gap-2 text-sky-400 text-xs font-bold uppercase tracking-widest svelte-178l0i5"><span class="w-2 h-2 rounded-full bg-sky-500 animate-ping svelte-178l0i5"></span> Terbuka untuk pengadaan</div></div> <div class="flex flex-wrap items-center gap-4 justify-center lg:justify-start svelte-178l0i5">`);
		if (profile?.cv_path) {
			$$renderer.push("<!--[0-->");
			$$renderer.push(`<a${attr("href", `/storage/${stringify(profile.cv_path)}`)} target="_blank" class="flex items-center gap-3 bg-slate-100 text-slate-900 px-8 py-4 rounded-2xl font-bold hover:bg-white hover:scale-105 active:scale-95 transition-all shadow-xl group svelte-178l0i5"><span class="svelte-178l0i5">Unduh CV</span> <svg class="w-5 h-5 group-hover:translate-y-1 transition-transform svelte-178l0i5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" class="svelte-178l0i5"></path></svg></a>`);
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--> `);
		if (profile?.phone) {
			$$renderer.push("<!--[0-->");
			$$renderer.push(`<a${attr("href", `https://wa.me/${stringify(formattedPhone())}`)} target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 bg-green-600 text-white px-8 py-4 rounded-2xl font-bold hover:bg-green-500 hover:scale-105 active:scale-95 transition-all shadow-xl shadow-green-600/20 group svelte-178l0i5"><svg class="w-6 h-6 fill-current svelte-178l0i5" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.588-5.946 0-6.556 5.332-11.888 11.888-11.888 3.176 0 6.161 1.237 8.404 3.48s3.481 5.229 3.481 8.406c0 6.555-5.332 11.887-11.887 11.887-2.01 0-3.987-.512-5.744-1.488l-6.141 1.712zm6.352-3.804c1.644.975 3.266 1.489 4.981 1.489 5.399 0 9.794-4.396 9.794-9.795 0-5.398-4.396-9.793-9.794-9.793-2.615 0-5.074 1.018-6.921 2.865s-2.864 4.305-2.864 6.92c0 1.761.533 3.436 1.543 4.887l-1.011 3.693 3.791-1.055c1.479.805 3.13 1.258 4.793 1.258zm11.314-7.462c-.302-.151-1.782-.879-2.059-.979-.277-.101-.48-.151-.68.151-.2.302-.779.979-.955 1.181-.177.201-.353.226-.654.076-.301-.151-1.272-.469-2.421-1.494-.894-.797-1.497-1.782-1.672-2.083-.177-.302-.019-.465.132-.615.136-.134.302-.352.453-.529.151-.176.201-.302.302-.503.101-.201.05-.378-.026-.529-.076-.151-.68-1.637-.931-2.242-.244-.589-.493-.509-.68-.518-.176-.008-.378-.01-.58-.01-.201 0-.529.076-.805.378-.277.301-1.056 1.031-1.056 2.515 0 1.484 1.08 2.919 1.231 3.12.151.201 2.126 3.246 5.148 4.549.719.31 1.28.496 1.718.636.721.23 1.378.197 1.896.12.577-.085 1.782-.729 2.034-1.433.251-.704.251-1.307.176-1.433-.076-.126-.277-.202-.579-.353z" class="svelte-178l0i5"></path></svg> <span class="svelte-178l0i5">Hubungi</span></a>`);
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></div></div></div></div> <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce opacity-20 hidden lg:block svelte-178l0i5"><svg class="w-6 h-6 svelte-178l0i5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" class="svelte-178l0i5"></path></svg></div></section> <section id="vision" class="py-32 bg-slate-900/30 svelte-178l0i5"><div class="container mx-auto px-6 svelte-178l0i5"><div class="text-center mb-20 reveal svelte-178l0i5"><h2 class="text-4xl lg:text-6xl font-bold tracking-tight mb-4 svelte-178l0i5">Visi &amp; <span class="text-sky-500 svelte-178l0i5">Misi</span></h2> <div class="w-24 h-1.5 bg-sky-500 mx-auto rounded-full shadow-[0_0_20px_rgba(14,165,233,0.5)] svelte-178l0i5"></div></div> <div class="max-w-5xl mx-auto mb-24 relative group reveal svelte-178l0i5"><div class="relative p-10 lg:p-20 bg-slate-900 border border-slate-800 rounded-[3rem] overflow-hidden shadow-2xl transition-all duration-500 hover:border-sky-500/30 svelte-178l0i5"><div class="absolute -top-24 -left-24 w-64 h-64 bg-sky-500/5 blur-[80px] rounded-full group-hover:bg-sky-500/10 transition-all duration-700 svelte-178l0i5"></div> <div class="relative z-10 svelte-178l0i5"><div class="flex items-center gap-4 mb-12 svelte-178l0i5"><div class="w-12 h-12 bg-sky-500/10 rounded-xl flex items-center justify-center text-sky-400 svelte-178l0i5"><svg class="w-6 h-6 svelte-178l0i5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" class="svelte-178l0i5"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" class="svelte-178l0i5"></path></svg></div> <span class="text-sky-500 font-bold uppercase tracking-[0.2em] text-xs svelte-178l0i5">Visi</span></div> <p class="text-3xl lg:text-5xl font-bold leading-tight text-white tracking-tight group-hover:text-sky-50 transition-colors duration-500 svelte-178l0i5">${escape_html(visionMission?.vision || "Menyediakan layanan berkualitas tinggi dengan solusi inovatif.")}</p></div></div></div> `);
		if (visionMission?.missions && Array.isArray(visionMission.missions)) {
			$$renderer.push("<!--[0-->");
			$$renderer.push(`<div class="mb-12 mt-20 relative z-10 reveal svelte-178l0i5"><div class="flex items-center gap-4 svelte-178l0i5"><div class="w-12 h-12 bg-sky-500/10 rounded-2xl flex items-center justify-center text-sky-400 border border-sky-500/20 svelte-178l0i5"><svg class="w-6 h-6 svelte-178l0i5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" class="svelte-178l0i5"></path></svg></div> <h3 class="text-3xl font-bold text-white tracking-tight uppercase svelte-178l0i5">Misi</h3></div></div> <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 svelte-178l0i5"><!--[-->`);
			const each_array_3 = ensure_array_like(visionMission.missions);
			for (let i = 0, $$length = each_array_3.length; i < $$length; i++) {
				let mission = each_array_3[i];
				$$renderer.push(`<div class="p-8 rounded-3xl bg-slate-900/80 border border-slate-800 hover:border-sky-500/50 transition-all group hover:-translate-y-2 duration-500 shadow-2xl relative overflow-hidden reveal svelte-178l0i5"><div class="absolute -right-4 -top-4 w-16 h-16 bg-sky-500/5 rounded-full blur-xl group-hover:bg-sky-500/10 transition-all svelte-178l0i5"></div> <div class="flex items-center gap-4 mb-6 svelte-178l0i5"><span class="text-sky-500 font-black text-3xl opacity-40 group-hover:opacity-100 transition-opacity svelte-178l0i5">0${escape_html(i + 1)}</span> <div class="h-[1px] flex-1 bg-slate-800 svelte-178l0i5"></div></div> <p class="text-lg text-white font-medium leading-relaxed relative z-10 svelte-178l0i5">${escape_html(mission)}</p></div>`);
			}
			$$renderer.push(`<!--]--></div>`);
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></div></section> <section id="services" class="py-32 relative svelte-178l0i5"><div class="container mx-auto px-6 svelte-178l0i5"><div class="text-center mb-20 reveal svelte-178l0i5"><h2 class="text-4xl lg:text-6xl font-bold tracking-tight mb-4 svelte-178l0i5">Melayani <span class="text-sky-500 svelte-178l0i5">Kebutuhan Digital</span> Anda</h2> <div class="w-24 h-1.5 bg-sky-500 mx-auto rounded-full shadow-[0_0_20px_rgba(14,165,233,0.5)] svelte-178l0i5"></div> <p class="mt-8 text-slate-400 max-w-2xl mx-auto svelte-178l0i5">Transformasi kebutuhan digital menjadi solusi yang efisien, mudah digunakan, dan handal</p></div> <div class="max-w-6xl mx-auto svelte-178l0i5"><div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 svelte-178l0i5"><!--[-->`);
		const each_array_4 = ensure_array_like(services);
		for (let i = 0, $$length = each_array_4.length; i < $$length; i++) {
			let service = each_array_4[i];
			$$renderer.push(`<div class="group relative bg-slate-900 border border-slate-800 p-8 lg:p-10 rounded-[2.5rem] transition-all duration-500 hover:border-sky-500/30 shadow-2xl overflow-hidden flex flex-col reveal svelte-178l0i5"><div class="mb-8 svelte-178l0i5"><div class="w-14 h-14 bg-sky-500/10 rounded-2xl flex items-center justify-center text-sky-400 mb-6 group-hover:scale-110 transition-all duration-500 border border-sky-500/10 group-hover:border-sky-500/40 shadow-inner svelte-178l0i5">${html(getServiceIcon(service.icon))}</div> <h3 class="text-2xl lg:text-3xl font-bold text-white group-hover:text-sky-400 transition-colors tracking-tighter mb-3 svelte-178l0i5">${escape_html(service.title)}</h3> <div class="h-1 w-10 bg-sky-500/20 rounded-full group-hover:w-20 group-hover:bg-sky-500 transition-all duration-500 svelte-178l0i5"></div></div> <div class="mb-8 svelte-178l0i5"><p class="text-[10px] text-slate-500 font-bold uppercase tracking-[0.2em] mb-1 svelte-178l0i5">Investment Range</p> <p class="text-xl font-black text-white tracking-tight svelte-178l0i5">${escape_html(service.price_range)}</p></div> <div class="flex-1 svelte-178l0i5"><div class="text-base text-slate-400 leading-relaxed font-light group-hover:text-slate-300 transition-colors whitespace-pre-line svelte-178l0i5">${escape_html(service.description)}</div></div> <div class="absolute -right-20 -top-20 w-64 h-64 bg-sky-500/5 blur-[100px] rounded-full pointer-events-none group-hover:bg-sky-500/10 transition-all duration-1000 svelte-178l0i5"></div> <div class="absolute inset-0 bg-gradient-to-br from-transparent via-transparent to-sky-500/[0.02] opacity-0 group-hover:opacity-100 transition-opacity duration-700 svelte-178l0i5"></div></div>`);
		}
		$$renderer.push(`<!--]--></div></div></div></section> <section id="projects" class="py-32 bg-slate-950/50 relative overflow-hidden svelte-178l0i5"><div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-sky-600/5 blur-[120px] rounded-full pointer-events-none svelte-178l0i5"></div> <div class="container mx-auto px-6 svelte-178l0i5"><div class="text-center mb-24 relative z-10 reveal svelte-178l0i5"><div class="flex items-center justify-center gap-3 mb-6 svelte-178l0i5"><div class="w-12 h-[1px] bg-sky-500/50 svelte-178l0i5"></div> <span class="text-sky-500 text-xs font-black uppercase tracking-[0.3em] svelte-178l0i5">Portofolio Expo</span> <div class="w-12 h-[1px] bg-sky-500/50 svelte-178l0i5"></div></div> <h2 class="text-5xl lg:text-7xl font-bold tracking-tighter mb-6 text-white svelte-178l0i5">${escape_html(projects.length)} Solusi <span class="text-sky-500 svelte-178l0i5">Telah Dibuat</span></h2> <div class="w-32 h-1.5 bg-sky-500 mx-auto rounded-full shadow-[0_0_25px_rgba(14,165,233,0.6)] svelte-178l0i5"></div> <div class="mt-12 relative max-w-xl mx-auto group svelte-178l0i5"><div class="absolute -inset-1 bg-gradient-to-r from-sky-500/20 to-indigo-500/20 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-1000 svelte-178l0i5"></div> <div class="relative flex items-center bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden svelte-178l0i5"><div class="pl-6 text-slate-500 svelte-178l0i5"><svg class="w-5 h-5 svelte-178l0i5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" class="svelte-178l0i5"></path></svg></div> <input type="text"${attr("value", searchQuery)} placeholder="Cari solusi atau proyek..." class="w-full bg-transparent border-none px-6 py-5 focus:ring-0 outline-none text-slate-100 font-medium placeholder:text-slate-600 svelte-178l0i5"/></div></div></div> <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-20 relative z-10 svelte-178l0i5">`);
		const each_array_5 = ensure_array_like(paginatedProjects());
		if (each_array_5.length !== 0) {
			$$renderer.push("<!--[-->");
			for (let i = 0, $$length = each_array_5.length; i < $$length; i++) {
				let project = each_array_5[i];
				$$renderer.push(`<a${attr("href", `/project/${stringify(project.slug)}`)} class="group relative flex flex-col bg-slate-900 border border-slate-800 rounded-3xl hover:border-sky-500/50 transition-all duration-500 shadow-xl overflow-hidden reveal svelte-178l0i5"><div class="relative aspect-[4/3] overflow-hidden svelte-178l0i5">`);
				if (project.thumbnail) {
					$$renderer.push("<!--[0-->");
					$$renderer.push(`<img${attr("src", `/storage/${stringify(project.thumbnail)}`)}${attr("alt", project.title)} class="w-full h-full object-cover group-hover:scale-110 transition-all duration-700 svelte-178l0i5"/>`);
				} else {
					$$renderer.push("<!--[-1-->");
					$$renderer.push(`<div class="w-full h-full bg-slate-800 flex items-center justify-center svelte-178l0i5"><span class="text-4xl opacity-10 svelte-178l0i5">📦</span></div>`);
				}
				$$renderer.push(`<!--]--> <div class="absolute top-4 left-4 z-10 svelte-178l0i5"><div class="bg-slate-950/80 backdrop-blur-md border border-white/10 px-3 py-1.5 rounded-xl flex flex-col items-center svelte-178l0i5"><span class="text-[10px] text-sky-400 font-bold uppercase tracking-widest svelte-178l0i5">${escape_html(project.month || "Jan")}</span> <span class="text-sm text-white font-bold svelte-178l0i5">${escape_html(project.year || "2024")}</span></div></div> `);
				if (project.is_opensource) {
					$$renderer.push("<!--[0-->");
					$$renderer.push(`<div class="absolute top-4 right-4 z-10 svelte-178l0i5"><div class="bg-sky-500 text-white px-3 py-1.5 rounded-xl flex items-center gap-2 shadow-lg shadow-sky-500/20 svelte-178l0i5"><svg class="w-4 h-4 svelte-178l0i5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" class="svelte-178l0i5"></path></svg> <span class="text-[10px] font-black uppercase tracking-wider svelte-178l0i5">Open Source</span></div></div>`);
				} else $$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]--> <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity svelte-178l0i5"></div></div> <div class="p-8 flex-1 flex flex-col svelte-178l0i5"><h3 class="text-xl font-bold mb-4 text-white group-hover:text-sky-400 transition-colors tracking-tight line-clamp-1 svelte-178l0i5">${escape_html(project.title)}</h3> <p class="text-slate-400 text-sm font-light leading-relaxed mb-8 line-clamp-2 flex-1 group-hover:text-slate-300 transition-colors svelte-178l0i5">${escape_html(project.short_description)}</p> <div class="flex items-center gap-2 text-sky-500 font-bold group-hover:gap-4 transition-all mt-auto group-hover:text-sky-400 svelte-178l0i5"><span class="svelte-178l0i5">baca lebih lanjut</span> <svg class="w-5 h-5 svelte-178l0i5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" class="svelte-178l0i5"></path></svg></div></div></a>`);
			}
		} else {
			$$renderer.push("<!--[!-->");
			$$renderer.push(`<div class="col-span-full text-center py-24 bg-slate-900/30 border border-slate-800 border-dashed rounded-[3rem] svelte-178l0i5"><div class="text-6xl mb-6 opacity-20 svelte-178l0i5">🔍</div> <p class="text-slate-500 text-xl font-light svelte-178l0i5">Tidak ada solusi ditemukan untuk <span class="text-sky-500 font-medium svelte-178l0i5">"${escape_html(searchQuery)}"</span></p></div>`);
		}
		$$renderer.push(`<!--]--></div> `);
		if (totalPages() > 1) {
			$$renderer.push("<!--[0-->");
			$$renderer.push(`<div class="flex justify-center items-center gap-6 relative z-10 svelte-178l0i5"><button${attr("disabled", currentPage === 1, true)} class="w-14 h-14 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-400 hover:text-sky-400 hover:border-sky-500/50 disabled:opacity-20 disabled:pointer-events-none transition-all shadow-xl svelte-178l0i5" aria-label="Halaman sebelumnya"><svg class="w-6 h-6 svelte-178l0i5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" class="svelte-178l0i5"></path></svg></button> <div class="flex items-center gap-3 svelte-178l0i5"><!--[-->`);
			const each_array_6 = ensure_array_like(Array(totalPages()));
			for (let i = 0, $$length = each_array_6.length; i < $$length; i++) {
				each_array_6[i];
				$$renderer.push(`<button${attr_class(`w-12 h-12 rounded-xl font-black text-xs transition-all ${stringify(currentPage === i + 1 ? "bg-sky-500 text-white shadow-lg shadow-sky-500/20 scale-110" : "bg-slate-900 border border-slate-800 text-slate-500 hover:text-white hover:border-slate-600")}`, "svelte-178l0i5")}${attr("aria-label", `Halaman ${stringify(i + 1)}`)}>${escape_html(i + 1)}</button>`);
			}
			$$renderer.push(`<!--]--></div> <button${attr("disabled", currentPage === totalPages(), true)} class="w-14 h-14 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-400 hover:text-sky-400 hover:border-sky-500/50 disabled:opacity-20 disabled:pointer-events-none transition-all shadow-xl svelte-178l0i5" aria-label="Halaman berikutnya"><svg class="w-6 h-6 svelte-178l0i5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" class="svelte-178l0i5"></path></svg></button></div>`);
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></div></section> <section id="skills" class="py-32 relative svelte-178l0i5"><div class="container mx-auto px-6 svelte-178l0i5"><div class="text-center mb-24 reveal svelte-178l0i5"><h2 class="text-5xl lg:text-7xl font-bold tracking-tighter mb-4 svelte-178l0i5">Bidang <span class="text-sky-500 svelte-178l0i5">Keahlian</span></h2> <div class="w-24 h-1.5 bg-sky-500 mx-auto rounded-full shadow-[0_0_20px_rgba(14,165,233,0.5)] svelte-178l0i5"></div></div> <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 lg:gap-16 svelte-178l0i5"><!--[-->`);
		const each_array_7 = ensure_array_like([
			"teknologi",
			"minat",
			"bahasa"
		]);
		for (let catIndex = 0, $$length = each_array_7.length; catIndex < $$length; catIndex++) {
			let cat = each_array_7[catIndex];
			$$renderer.push(`<div class="flex flex-col reveal svelte-178l0i5"><div class="flex items-center gap-4 mb-10 pb-6 border-b border-slate-800/50 svelte-178l0i5"><div class="w-14 h-14 rounded-2xl bg-sky-500/10 flex items-center justify-center text-3xl shadow-inner border border-sky-500/10 svelte-178l0i5">${escape_html(cat === "bahasa" ? "🌐" : cat === "teknologi" ? "⚡" : "✨")}</div> <div class="svelte-178l0i5"><h3 class="text-2xl font-bold text-white tracking-tight svelte-178l0i5">${escape_html(getCategoryLabel(cat))}</h3></div></div> <div class="space-y-6 svelte-178l0i5">`);
			if (categorizedSkills()[cat]) {
				$$renderer.push("<!--[0-->");
				$$renderer.push(`<!--[-->`);
				const each_array_8 = ensure_array_like(categorizedSkills()[cat]);
				for (let i = 0, $$length = each_array_8.length; i < $$length; i++) {
					let skill = each_array_8[i];
					$$renderer.push(`<div class="group relative p-6 rounded-[2rem] bg-slate-900/50 border border-slate-800 hover:border-sky-500/30 transition-all duration-500 shadow-xl overflow-hidden hover:-translate-y-1 reveal svelte-178l0i5"><div class="absolute -right-8 -bottom-8 w-24 h-24 bg-sky-500/5 rounded-full blur-2xl group-hover:bg-sky-500/10 transition-all duration-700 svelte-178l0i5"></div> <div class="relative z-10 svelte-178l0i5"><div class="flex items-center gap-5 mb-4 svelte-178l0i5"><div class="w-12 h-12 rounded-xl bg-slate-950 flex items-center justify-center p-2.5 border border-slate-800 group-hover:border-sky-500/50 transition-colors duration-500 svelte-178l0i5">`);
					if (skill.logo_path) {
						$$renderer.push("<!--[0-->");
						$$renderer.push(`<img${attr("src", `/storage/${stringify(skill.logo_path)}`)}${attr("alt", skill.title)} class="max-w-full max-h-full object-contain svelte-178l0i5"/>`);
					} else {
						$$renderer.push("<!--[-1-->");
						$$renderer.push(`<span class="text-xl svelte-178l0i5">✨</span>`);
					}
					$$renderer.push(`<!--]--></div> <h4 class="text-lg font-bold text-white group-hover:text-sky-400 transition-colors duration-300 tracking-tight svelte-178l0i5">${escape_html(skill.title)}</h4></div> <p class="text-sm text-slate-400 leading-relaxed font-light line-clamp-3 group-hover:text-slate-300 transition-colors duration-500 svelte-178l0i5">${escape_html(skill.description || "Keahlian profesional dalam bidang ini untuk mendukung solusi digital.")}</p></div></div>`);
				}
				$$renderer.push(`<!--]-->`);
			} else {
				$$renderer.push("<!--[-1-->");
				$$renderer.push(`<div class="p-8 rounded-[2rem] border border-slate-800 border-dashed text-center svelte-178l0i5"><p class="text-slate-600 text-sm italic svelte-178l0i5">Belum ada data</p></div>`);
			}
			$$renderer.push(`<!--]--></div></div>`);
		}
		$$renderer.push(`<!--]--></div></div></section> <section id="experience" class="py-32 bg-slate-950/50 relative overflow-hidden svelte-178l0i5"><div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-sky-500/5 blur-[120px] rounded-full pointer-events-none svelte-178l0i5"></div> <div class="container mx-auto px-6 relative z-10 svelte-178l0i5"><h2 class="text-5xl lg:text-7xl font-bold mb-32 tracking-tighter flex flex-col items-center justify-center gap-2 text-center reveal svelte-178l0i5"><span class="text-sky-500 text-2xl font-mono mb-4 tracking-[0.3em] uppercase opacity-50 svelte-178l0i5">Jenjang Karir</span> Pengalaman <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-indigo-500 svelte-178l0i5">Profesional</span></h2> <div class="relative max-w-6xl mx-auto svelte-178l0i5"><div class="absolute left-8 lg:left-1/2 top-0 bottom-0 w-px bg-gradient-to-b from-transparent via-slate-800 to-transparent lg:-translate-x-1/2 svelte-178l0i5"><div class="sticky top-1/2 w-1 h-20 bg-gradient-to-b from-sky-500 to-indigo-500 -ml-[1.5px] blur-[2px] opacity-50 svelte-178l0i5"></div></div> <div class="space-y-24 svelte-178l0i5"><!--[-->`);
		const each_array_9 = ensure_array_like(experiences);
		for (let i = 0, $$length = each_array_9.length; i < $$length; i++) {
			let exp = each_array_9[i];
			$$renderer.push(`<div${attr_class(`relative flex flex-col lg:flex-row items-center ${stringify(i % 2 === 0 ? "lg:flex-row-reverse" : "")}`, "svelte-178l0i5")}><div class="absolute left-8 lg:left-1/2 top-0 w-6 h-6 -translate-x-1/2 z-20 flex items-center justify-center svelte-178l0i5"><div class="w-full h-full rounded-full bg-slate-950 border border-slate-800 group-hover:border-sky-500 transition-colors relative svelte-178l0i5"><div class="absolute inset-1 rounded-full bg-sky-500 shadow-[0_0_15px_rgba(14,165,233,0.5)] svelte-178l0i5"></div></div></div> <div class="w-full lg:w-[45%] pl-20 lg:pl-0 svelte-178l0i5"><div class="group relative p-8 rounded-[2.5rem] bg-slate-900/50 backdrop-blur-sm border border-slate-800 hover:border-sky-500/30 transition-all duration-500 hover:-translate-y-2 reveal svelte-178l0i5"><div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-sky-500/10 border border-sky-500/20 text-sky-400 text-xs font-black uppercase tracking-widest mb-6 svelte-178l0i5"><span class="relative flex h-2 w-2 svelte-178l0i5"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75 svelte-178l0i5"></span> <span class="relative inline-flex rounded-full h-2 w-2 bg-sky-500 svelte-178l0i5"></span></span> ${escape_html(exp.start_date)} — ${escape_html(exp.end_date || "Sekarang")}</div> <h3 class="text-3xl font-bold mb-3 text-white group-hover:text-sky-400 transition-colors leading-tight svelte-178l0i5">${escape_html(exp.position)}</h3> <div class="flex flex-wrap items-center gap-3 text-slate-400 mb-6 svelte-178l0i5"><div class="flex items-center gap-2 px-3 py-1 rounded-lg bg-slate-950 border border-slate-800 svelte-178l0i5"><span class="text-white font-bold svelte-178l0i5">${escape_html(exp.company)}</span></div> <span class="w-1 h-1 rounded-full bg-slate-700 svelte-178l0i5"></span> <span class="text-sm font-medium text-slate-500 italic svelte-178l0i5">${escape_html(exp.location_text)}</span> <span class="px-2 py-0.5 rounded text-[10px] font-black bg-slate-800 text-slate-400 uppercase tracking-tighter border border-slate-700 svelte-178l0i5">${escape_html(exp.status)}</span></div> <p class="text-slate-400 font-light leading-relaxed whitespace-pre-line text-sm lg:text-base svelte-178l0i5">${escape_html(exp.description)}</p> <div class="absolute bottom-0 left-10 right-10 h-px bg-gradient-to-r from-transparent via-sky-500/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700 svelte-178l0i5"></div></div></div> <div class="hidden lg:block lg:w-[45%] svelte-178l0i5"></div></div>`);
		}
		$$renderer.push(`<!--]--></div></div></div></section> <section id="education" class="py-32 relative overflow-hidden svelte-178l0i5"><div class="absolute bottom-0 left-0 w-full h-1/2 bg-gradient-to-t from-indigo-500/5 to-transparent pointer-events-none svelte-178l0i5"></div> <div class="container mx-auto px-6 relative z-10 svelte-178l0i5"><h2 class="text-5xl lg:text-7xl font-bold mb-32 tracking-tighter flex flex-col items-center justify-center gap-2 text-center reveal svelte-178l0i5">Perjalanan <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-500 svelte-178l0i5">Pendidikan</span></h2> <div class="relative max-w-6xl mx-auto svelte-178l0i5"><div class="absolute left-8 lg:left-1/2 top-0 bottom-0 w-px bg-gradient-to-b from-transparent via-slate-800 to-transparent lg:-translate-x-1/2 svelte-178l0i5"><div class="absolute inset-0 bg-gradient-to-b from-indigo-500/20 via-transparent to-purple-500/20 svelte-178l0i5"></div></div> <div class="space-y-24 svelte-178l0i5"><!--[-->`);
		const each_array_10 = ensure_array_like(education);
		for (let i = 0, $$length = each_array_10.length; i < $$length; i++) {
			let edu = each_array_10[i];
			$$renderer.push(`<div${attr_class(`relative flex flex-col lg:flex-row items-center ${stringify(i % 2 !== 0 ? "lg:flex-row-reverse" : "")}`, "svelte-178l0i5")}><div class="absolute left-8 lg:left-1/2 top-0 w-6 h-6 -translate-x-1/2 z-20 flex items-center justify-center svelte-178l0i5"><div class="w-full h-full rounded-full bg-slate-950 border border-slate-800 group-hover:border-indigo-500 transition-colors relative svelte-178l0i5"><div class="absolute inset-1 rounded-full bg-indigo-500 shadow-[0_0_15px_rgba(99,102,241,0.5)] svelte-178l0i5"></div></div></div> <div class="w-full lg:w-[45%] pl-20 lg:pl-0 svelte-178l0i5"><div class="group relative p-8 rounded-[2.5rem] bg-slate-900/30 backdrop-blur-sm border border-slate-800/50 hover:border-indigo-500/30 transition-all duration-500 reveal svelte-178l0i5"><div class="inline-block px-4 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs font-black mb-6 svelte-178l0i5">🎓 Lulus: ${escape_html(edu.graduation_date)}</div> <h3 class="text-3xl font-black mb-3 text-white group-hover:text-indigo-400 transition-colors tracking-tight svelte-178l0i5">${escape_html(edu.major)}</h3> <div class="flex items-center gap-3 text-slate-400 mb-6 font-medium svelte-178l0i5"><span class="text-slate-200 svelte-178l0i5">${escape_html(edu.institution)}</span> `);
			if (edu.degree) {
				$$renderer.push("<!--[0-->");
				$$renderer.push(`<span class="w-1 h-1 rounded-full bg-slate-700 svelte-178l0i5"></span> <span class="text-indigo-300/70 text-sm tracking-wide svelte-178l0i5">${escape_html(edu.degree)}</span>`);
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--></div> <div class="flex items-center justify-between pt-6 border-t border-slate-800/50 svelte-178l0i5"><div class="flex items-center gap-2 text-slate-500 text-sm svelte-178l0i5"><svg class="w-4 h-4 text-indigo-500 svelte-178l0i5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" class="svelte-178l0i5"></path></svg> ${escape_html(edu.location_text)}</div> `);
			if (edu.gpa) {
				$$renderer.push("<!--[0-->");
				$$renderer.push(`<div class="flex flex-col items-end svelte-178l0i5"><span class="text-[10px] text-slate-500 uppercase font-black tracking-tighter svelte-178l0i5">Grade Point</span> <span class="text-xl font-black text-indigo-400 svelte-178l0i5">${escape_html(edu.gpa)}</span></div>`);
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--></div> <div class="absolute -bottom-2 -right-2 w-20 h-20 bg-indigo-500/10 blur-2xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity svelte-178l0i5"></div></div></div> <div class="hidden lg:block lg:w-[45%] svelte-178l0i5"></div></div>`);
		}
		$$renderer.push(`<!--]--></div></div></div></section> <section id="certificates" class="py-32 relative overflow-hidden svelte-178l0i5"><div class="absolute top-1/2 right-0 w-[500px] h-[500px] bg-sky-500/5 blur-[120px] rounded-full pointer-events-none svelte-178l0i5"></div> <div class="container mx-auto px-6 relative z-10 svelte-178l0i5"><div class="text-center mb-24 reveal svelte-178l0i5"><h2 class="text-5xl lg:text-7xl font-black tracking-tighter mb-4 svelte-178l0i5">Pelatihan &amp; <span class="text-sky-500 svelte-178l0i5">Sertifikasi</span></h2> <div class="w-24 h-1.5 bg-sky-500 mx-auto rounded-full shadow-[0_0_20px_rgba(14,165,233,0.5)] svelte-178l0i5"></div></div> <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto svelte-178l0i5">`);
		const each_array_11 = ensure_array_like(certificates);
		if (each_array_11.length !== 0) {
			$$renderer.push("<!--[-->");
			for (let i = 0, $$length = each_array_11.length; i < $$length; i++) {
				let cert = each_array_11[i];
				$$renderer.push(`<div class="group relative p-10 rounded-[2rem] bg-slate-900/40 backdrop-blur-md border border-slate-800/50 hover:border-sky-500/30 transition-all duration-700 flex flex-col h-full overflow-hidden reveal svelte-178l0i5"><div class="absolute left-0 top-10 bottom-10 w-[2px] bg-sky-500/30 group-hover:bg-sky-500 transition-colors duration-700 svelte-178l0i5"></div> <div class="flex-1 relative z-10 pl-4 svelte-178l0i5"><h3 class="text-2xl font-black mb-2 text-white group-hover:text-sky-400 transition-colors leading-tight tracking-tight mt-2 svelte-178l0i5">${escape_html(cert.title)}</h3> <p class="text-slate-400 font-bold mb-8 text-sm svelte-178l0i5">${escape_html(cert.issuer)}</p> <div class="flex flex-wrap gap-2 svelte-178l0i5"><div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-sky-500/5 border border-sky-500/10 text-[10px] font-black text-sky-500/80 uppercase tracking-widest svelte-178l0i5"><span class="w-1.5 h-1.5 rounded-full bg-sky-500 animate-pulse svelte-178l0i5"></span> ${escape_html(cert.level)}</div> <div class="inline-flex items-center px-3 py-1 rounded-lg bg-slate-950 border border-slate-800 text-[10px] font-black text-slate-500 uppercase tracking-widest svelte-178l0i5">${escape_html(cert.category)}</div></div></div> <div class="mt-10 pt-6 border-t border-slate-800/50 pl-4 relative z-10 flex items-center justify-between svelte-178l0i5"><div class="flex items-center gap-2 svelte-178l0i5"><svg class="w-3.5 h-3.5 text-sky-500/50 svelte-178l0i5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" class="svelte-178l0i5"></path></svg> <p class="text-xs text-slate-400 font-medium tracking-tight svelte-178l0i5">${escape_html(cert.start_date)} — ${escape_html(cert.end_date || "Seumur Hidup")}</p></div></div> <div class="absolute inset-0 bg-gradient-to-br from-sky-500/[0.03] to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-1000 svelte-178l0i5"></div></div>`);
			}
		} else {
			$$renderer.push("<!--[!-->");
			$$renderer.push(`<div class="col-span-full py-20 text-center svelte-178l0i5"><div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-900 border border-slate-800 mb-6 svelte-178l0i5"><span class="text-3xl opacity-20 svelte-178l0i5">📭</span></div> <p class="text-slate-500 font-medium italic svelte-178l0i5">Belum ada data sertifikat yang tersedia.</p></div>`);
		}
		$$renderer.push(`<!--]--></div></div></section> <footer id="contact" class="pt-32 pb-16 bg-slate-950/80 border-t border-slate-900/50 relative overflow-hidden svelte-178l0i5"><div class="absolute -top-24 left-1/2 -translate-x-1/2 w-full h-px bg-gradient-to-r from-transparent via-sky-500/20 to-transparent svelte-178l0i5"></div> <div class="absolute bottom-0 left-1/4 w-[400px] h-[400px] bg-sky-600/5 blur-[120px] rounded-full pointer-events-none svelte-178l0i5"></div> <div class="container mx-auto px-6 relative z-10 svelte-178l0i5"><div class="flex flex-col lg:flex-row justify-between gap-16 lg:gap-8 pt-20 border-t border-slate-900 svelte-178l0i5"><div class="max-w-sm space-y-8 reveal svelte-178l0i5"><div class="flex items-center gap-3 svelte-178l0i5"><img src="/icon.webp" alt="Logo" class="w-12 h-12 rounded-xl shadow-lg shadow-sky-500/10 svelte-178l0i5"/> <span class="text-2xl font-black tracking-tighter text-white uppercase svelte-178l0i5">${escape_html(profile?.full_name || "Wufy Portfolio")}</span></div> <p class="text-slate-400 font-light leading-relaxed text-sm svelte-178l0i5">Berfokus pada pengembangan solusi digital yang inovatif, efisien, dan berorientasi pada hasil untuk membantu bisnis Anda tumbuh lebih cepat.</p> <div class="flex items-center gap-3 svelte-178l0i5">`);
		if (profile?.links) {
			$$renderer.push("<!--[0-->");
			$$renderer.push(`<!--[-->`);
			const each_array_12 = ensure_array_like(profile.links);
			for (let $$index_12 = 0, $$length = each_array_12.length; $$index_12 < $$length; $$index_12++) {
				let link = each_array_12[$$index_12];
				$$renderer.push(`<a${attr("href", link.link)} target="_blank" rel="noopener noreferrer" class="w-11 h-11 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-400 hover:text-sky-400 hover:border-sky-500/50 hover:bg-sky-500/5 transition-all group shadow-xl svelte-178l0i5">${html(getIcon(link.title))}</a>`);
			}
			$$renderer.push(`<!--]-->`);
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></div></div> <div class="min-w-[280px] reveal svelte-178l0i5"><h4 class="text-white font-black uppercase tracking-widest text-sm mb-8 svelte-178l0i5">Navigasi</h4> <ul class="grid grid-cols-2 gap-y-4 gap-x-12 svelte-178l0i5"><!--[-->`);
		const each_array_13 = ensure_array_like([
			{
				id: "home",
				label: "Beranda"
			},
			{
				id: "vision",
				label: "Visi/Misi"
			},
			{
				id: "services",
				label: "Jasa"
			},
			{
				id: "projects",
				label: "Projek"
			},
			{
				id: "skills",
				label: "Keahlian"
			},
			{
				id: "experience",
				label: "Pengalaman"
			},
			{
				id: "education",
				label: "Edukasi"
			},
			{
				id: "certificates",
				label: "Sertifikat"
			},
			{
				id: "contact",
				label: "Kontak"
			}
		]);
		for (let $$index_13 = 0, $$length = each_array_13.length; $$index_13 < $$length; $$index_13++) {
			let item = each_array_13[$$index_13];
			$$renderer.push(`<li class="svelte-178l0i5"><button class="text-slate-400 hover:text-sky-400 transition-colors text-sm font-medium whitespace-nowrap text-left svelte-178l0i5">${escape_html(item.label)}</button></li>`);
		}
		$$renderer.push(`<!--]--></ul></div> <div class="min-w-[240px] reveal svelte-178l0i5"><h4 class="text-white font-black uppercase tracking-widest text-sm mb-8 svelte-178l0i5">Kontak</h4> <div class="space-y-6 svelte-178l0i5"><div class="flex items-start gap-4 svelte-178l0i5"><div class="w-10 h-10 rounded-xl bg-sky-500/10 flex items-center justify-center text-sky-400 border border-sky-500/20 shrink-0 svelte-178l0i5"><svg class="w-5 h-5 svelte-178l0i5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" class="svelte-178l0i5"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" class="svelte-178l0i5"></path></svg></div> <div class="svelte-178l0i5"><p class="text-sky-500 text-[10px] font-semibold uppercase tracking-[0.2em] mb-1 svelte-178l0i5">Lokasi</p> <p class="text-white text-base font-medium tracking-tight svelte-178l0i5">${escape_html(profile?.city)}, ${escape_html(profile?.province)}</p></div></div> <div class="flex items-start gap-4 svelte-178l0i5"><div class="w-10 h-10 rounded-xl bg-sky-500/10 flex items-center justify-center text-sky-400 border border-sky-500/20 shrink-0 svelte-178l0i5"><svg class="w-5 h-5 svelte-178l0i5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" class="svelte-178l0i5"></path></svg></div> <div class="svelte-178l0i5"><p class="text-sky-500 text-[10px] font-semibold uppercase tracking-[0.2em] mb-1 svelte-178l0i5">Telepon</p> <p class="text-white text-base font-medium tracking-tight svelte-178l0i5">${escape_html(profile?.phone)}</p></div></div> <div class="flex items-start gap-4 svelte-178l0i5"><div class="w-10 h-10 rounded-xl bg-sky-500/10 flex items-center justify-center text-sky-400 border border-sky-500/20 shrink-0 svelte-178l0i5"><svg class="w-5 h-5 svelte-178l0i5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" class="svelte-178l0i5"></path></svg></div> <div class="svelte-178l0i5"><p class="text-sky-500 text-[10px] font-semibold uppercase tracking-[0.2em] mb-1 svelte-178l0i5">Email</p> <p class="text-white text-base font-medium tracking-tight svelte-178l0i5">${escape_html(profile?.email)}</p></div></div></div></div></div> <div class="mt-32 pt-8 border-t border-slate-900 flex flex-col md:flex-row justify-between items-center gap-6 reveal svelte-178l0i5"><p class="text-slate-500 text-xs font-medium tracking-wide svelte-178l0i5">© ${escape_html((/* @__PURE__ */ new Date()).getFullYear())} <span class="text-slate-300 font-bold svelte-178l0i5">${escape_html(profile?.full_name || "Wufy Portfolio")}</span>. All rights reserved.</p></div></div></footer></div>`);
	});
}
//#endregion
//#region node_modules/@inertiajs/svelte/dist/components/Render.svelte
var h = (component, propsOrChildren, childrenOrKey, key = null) => {
	const hasProps = typeof propsOrChildren === "object" && propsOrChildren !== null && !Array.isArray(propsOrChildren);
	return {
		component,
		key: hasProps ? key : typeof childrenOrKey === "number" ? childrenOrKey : null,
		props: hasProps ? propsOrChildren : {},
		children: hasProps ? Array.isArray(childrenOrKey) ? childrenOrKey : childrenOrKey !== null ? [childrenOrKey] : [] : Array.isArray(propsOrChildren) ? propsOrChildren : propsOrChildren !== null ? [propsOrChildren] : []
	};
};
function Render_1($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		const { component, props = {}, children = [], key = null } = $$props;
		if (component) {
			$$renderer.push("<!--[0-->");
			$$renderer.push(`<!---->`);
			if (children.length > 0) {
				$$renderer.push("<!--[0-->");
				const SvelteComponent = component;
				if (SvelteComponent) {
					$$renderer.push("<!--[-->");
					SvelteComponent($$renderer, spread_props([props, {
						children: ($$renderer) => {
							$$renderer.push(`<!--[-->`);
							const each_array = ensure_array_like(children);
							for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
								let child = each_array[$$index];
								Render_1($$renderer, spread_props([child]));
							}
							$$renderer.push(`<!--]-->`);
						},
						$$slots: { default: true }
					}]));
					$$renderer.push("<!--]-->");
				} else {
					$$renderer.push("<!--[!-->");
					$$renderer.push("<!--]-->");
				}
			} else {
				$$renderer.push("<!--[-1-->");
				const SvelteComponent_1 = component;
				if (SvelteComponent_1) {
					$$renderer.push("<!--[-->");
					SvelteComponent_1($$renderer, spread_props([props]));
					$$renderer.push("<!--]-->");
				} else {
					$$renderer.push("<!--[!-->");
					$$renderer.push("<!--]-->");
				}
			}
			$$renderer.push(`<!--]-->`);
			$$renderer.push(`<!---->`);
		} else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]-->`);
	});
}
//#endregion
//#region node_modules/@inertiajs/svelte/dist/page.svelte.js
var page = {
	component: "",
	props: {},
	url: "",
	version: null
};
function setPage(newPage) {
	Object.assign(page, newPage);
}
//#endregion
//#region node_modules/@inertiajs/svelte/dist/layoutProps.svelte.js
var store = createLayoutPropsStore();
var storeState = {
	shared: {},
	named: {}
};
store.subscribe(() => {
	const snapshot = store.get();
	storeState.shared = snapshot.shared;
	storeState.named = snapshot.named;
});
function resetLayoutProps() {
	store.reset();
	const snapshot = store.get();
	storeState.shared = snapshot.shared;
	storeState.named = snapshot.named;
}
//#endregion
//#region node_modules/@inertiajs/svelte/dist/components/App.svelte
function App($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		const { initialComponent, initialPage, resolveComponent, defaultLayout } = $$props;
		let component = initialComponent;
		let key = null;
		let page = {
			...initialPage,
			flash: initialPage.flash ?? {}
		};
		let renderProps = derived(() => resolveRenderProps(component, page, key));
		setPage(page);
		const isServer = typeof window === "undefined";
		if (!isServer) router.init({
			initialPage,
			resolveComponent,
			swapComponent: async (args) => {
				component = args.component;
				page = args.page;
				key = args.preserveState ? key : Date.now();
				if (!args.preserveState) resetLayoutProps();
			},
			onFlash: (flash) => {
				page = {
					...page,
					flash
				};
			}
		});
		function isComponent(value) {
			if (!value) return false;
			if (typeof value === "function") return value.name !== "";
			if (typeof value === "object" && "$$" in value) return true;
			return false;
		}
		function isRenderFunction(value) {
			return typeof value === "function" && value.length === 2 && typeof value.prototype === "undefined";
		}
		function resolveRenderProps(component, page, key = null) {
			const child = h(component.default, page.props, [], key);
			if (component.layout && isRenderFunction(component.layout)) return component.layout(h, child);
			let effectiveLayout;
			let callbackProps = null;
			const layoutValue = component.layout;
			if (typeof layoutValue === "function" && layoutValue.length <= 1 && typeof layoutValue.prototype === "undefined") {
				const result = layoutValue(page.props);
				if (isPropsObjectOrCallback(result, isComponent)) {
					effectiveLayout = defaultLayout?.(page.component, page);
					callbackProps = result;
				} else effectiveLayout = result;
			} else if (isPropsObject(layoutValue, isComponent)) {
				effectiveLayout = defaultLayout?.(page.component, page);
				callbackProps = layoutValue;
			} else effectiveLayout = layoutValue ?? defaultLayout?.(page.component, page);
			return effectiveLayout ? resolveLayout(effectiveLayout, child, page.props, key, !!component.layout && !callbackProps, callbackProps) : child;
		}
		function resolveLayout(layout, child, pageProps, key, isFromPage = true, callbackProps = null) {
			if (isFromPage && isRenderFunction(layout)) return layout(h, child);
			let layouts = normalizeLayouts(layout, isComponent, isFromPage ? isRenderFunction : void 0);
			if (callbackProps) layouts = layouts.map((l) => ({
				...l,
				props: {
					...l.props,
					...callbackProps
				}
			}));
			if (layouts.length > 0) {
				const dynamicProps = isServer ? {
					shared: {},
					named: {}
				} : {
					shared: storeState.shared,
					named: storeState.named
				};
				return layouts.reduceRight((child, layout) => {
					return {
						...h(layout.component, {
							...pageProps,
							...layout.props,
							...dynamicProps.shared,
							...layout.name ? dynamicProps.named[layout.name] || {} : {}
						}, [child], key),
						name: layout.name
					};
				}, child);
			}
			return child;
		}
		Render_1($$renderer, spread_props([renderProps()]));
	});
}
//#endregion
//#region node_modules/@inertiajs/svelte/dist/components/formContext.js
var [getFormContext, setFormContext] = createContext();
//#endregion
//#region node_modules/@inertiajs/svelte/dist/createInertiaApp.js
async function createInertiaApp({ id = "app", resolve, setup, progress = {}, page, defaults = {}, http: http$1, layout, withApp } = {}) {
	config$1.replace(defaults);
	if (http$1) http.setClient(http$1);
	const isServer = typeof window === "undefined";
	const resolveComponent = (name, page) => Promise.resolve(resolve(name, page));
	if (isServer && !page) return async (page, render) => {
		const props = {
			initialPage: page,
			initialComponent: await resolveComponent(page.component, page),
			resolveComponent,
			defaultLayout: layout
		};
		let svelteApp;
		if (setup) {
			const result = await setup({
				el: null,
				App,
				props
			});
			if (!result) throw new Error("Inertia SSR setup function must return a render result ({ body, head })");
			svelteApp = result;
		} else {
			const context = /* @__PURE__ */ new Map();
			if (withApp) withApp(context, { ssr: true });
			svelteApp = render(App, {
				props,
				context
			});
		}
		return {
			body: buildSSRBody(id, page, svelteApp.body),
			head: [svelteApp.head]
		};
	};
	const initialPage = page || getInitialPageFromDOM(id);
	const [initialComponent] = await Promise.all([resolveComponent(initialPage.component, initialPage), router.decryptHistory().catch(() => {})]);
	const props = {
		initialPage,
		initialComponent,
		resolveComponent,
		defaultLayout: layout
	};
	if (isServer) {
		if (!setup) throw new Error("Inertia SSR requires a setup function that returns a render result ({ body, head })");
		const svelteApp = await setup({
			el: null,
			App,
			props
		});
		if (svelteApp) return {
			body: buildSSRBody(id, initialPage, svelteApp.body),
			head: [svelteApp.head]
		};
		return;
	}
	const target = document.getElementById(id);
	if (setup) await setup({
		el: target,
		App,
		props
	});
	else {
		const context = /* @__PURE__ */ new Map();
		if (withApp) withApp(context, { ssr: false });
		if (target.hasAttribute("data-server-rendered")) hydrate(App, {
			target,
			props,
			context
		});
		else mount(App, {
			target,
			props,
			context
		});
	}
	if (progress) setupProgress(progress);
}
//#endregion
//#region node_modules/@inertiajs/svelte/dist/index.js
var config$1 = config.extend({});
//#endregion
//#region resources/js/ssr.js
createInertiaApp({
	page: (page) => page,
	render: server_default,
	resolve: (name) => {
		return (/* @__PURE__ */ Object.assign({
			"./Pages/Error.svelte": Error_exports,
			"./Pages/ProjectDetail.svelte": ProjectDetail_exports,
			"./Pages/Welcome.svelte": Welcome_exports
		}))[`./Pages/${name}.svelte`];
	},
	setup({ App, props }) {
		return App({ props });
	}
});
//#endregion
export {};
