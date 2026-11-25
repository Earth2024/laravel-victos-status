(function (global) {
  'use strict';

  var DEFAULT_SELECTOR = '[data-victos-check-status]';
  var STYLE_ID = 'victos-check-status-button-styles';

  function addStyles() {
    if (document.getElementById(STYLE_ID)) return;
    var style = document.createElement('style');
    style.id = STYLE_ID;
    style.textContent =
      '.vcs-button{all:unset;box-sizing:border-box;display:inline-flex;align-items:center;justify-content:center;appearance:none;border:0;border-radius:999px;padding:11px 18px;font:700 14px/1.2 system-ui,sans-serif;cursor:pointer;box-shadow:0 8px 22px rgba(37,99,235,.22);transition:transform .15s,filter .15s}' +
      '.vcs-button:hover{transform:translateY(-1px);filter:brightness(1.06)}' +
      '.vcs-button:focus-visible{outline:3px solid rgba(99,102,241,.45);outline-offset:3px}';
    (document.head || document.documentElement).appendChild(style);
  }

  function resolveTargets(target) {
    if (!target) return Array.prototype.slice.call(document.querySelectorAll(DEFAULT_SELECTOR));
    if (typeof target === 'string') return Array.prototype.slice.call(document.querySelectorAll(target));
    if (target instanceof Element) return [target];
    if (Array.isArray(target)) return target.filter(function (item) { return item instanceof Element; });
    return [];
  }

  function buildUrl(base, params) {
    var url = new URL(base, document.baseURI);
    Object.keys(params).forEach(function (key) {
      if (params[key] !== undefined && params[key] !== null && params[key] !== '') {
        url.searchParams.set(key, params[key]);
      }
    });
    return url.toString();
  }

  function mount(target, options) {
    if (target.__victosStatusWidget) return target.__victosStatusWidget;

    var settings = Object.assign({}, options, {
      endpoint: options.endpoint || target.dataset.endpoint || '/check-status',
      tenantKey: options.tenantKey || target.dataset.tenantKey || '',
      buttonText: options.buttonText || target.dataset.buttonText || 'Check Status'
    });
    var button = document.createElement('button');
    button.type = 'button';
    button.className = 'vcs-button ' + (settings.buttonClass || '');
    button.textContent = settings.buttonText;
    button.setAttribute('aria-haspopup', 'dialog');
    button.setAttribute('aria-label', settings.buttonText);
    button.style.color = settings.buttonTextColor || '#fff';
    button.style.backgroundColor = settings.buttonBackgroundColor || '#2563eb';
    button.style.fontSize = settings.buttonFontSize || '14px';
    target.appendChild(button);

    var overlay = document.createElement('div');
    overlay.className = 'vcs-overlay';
    overlay.setAttribute('aria-hidden', 'true');
    var shadowRoot = overlay.attachShadow({ mode: 'open' });
    shadowRoot.innerHTML =
      '<style>' +
      ':host{font:15px/1.5 system-ui,-apple-system,"Segoe UI",sans-serif;color:#172033;color-scheme:light}' +
      '*,*::before,*::after{box-sizing:border-box}' +
      '.vcs-backdrop{position:absolute;inset:0;z-index:0;background:rgba(8,13,28,.74);backdrop-filter:blur(8px)}' +
      '.vcs-dialog{position:relative;z-index:1;width:min(100%,480px);max-height:min(90vh,720px);overflow:auto;margin:0;padding:25px;border:1px solid rgba(226,232,240,.9);border-radius:20px;background:#fff;color:#172033;box-shadow:0 28px 90px rgba(0,0,0,.32);text-align:left;animation:vcs-enter .18s ease-out}' +
      '@keyframes vcs-enter{from{opacity:0;transform:translateY(8px) scale(.99)}to{opacity:1;transform:translateY(0) scale(1)}}' +
      '.vcs-header{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin:0 0 9px}' +
      '.vcs-title{margin:0;color:#101828;font-size:21px;line-height:1.3;font-weight:750;letter-spacing:-.035em}' +
      '.vcs-close{flex:0 0 38px;width:38px;height:38px;display:grid;place-items:center;margin:-5px -5px 0 0;padding:0;border:1px solid #e4e9f1;border-radius:11px;background:#f3f5f9;color:#526174;font:400 21px/1 system-ui,sans-serif;cursor:pointer;transition:background .15s,border-color .15s,color .15s,transform .15s}' +
      '.vcs-close:hover{transform:rotate(90deg);border-color:#cdd7e5;background:#e9eef6;color:#172033}' +
      '.vcs-close:focus-visible,.vcs-submit:focus-visible{outline:3px solid rgba(79,70,229,.32);outline-offset:2px}' +
      '.vcs-description{margin:0 0 20px;color:#667085;font-size:13px;line-height:1.55}' +
      '.vcs-form{display:block;margin:0}' +
      '.vcs-form[hidden]{display:none}' +
      '.vcs-form label{display:block;margin:0 0 8px;color:#344054;font-size:13px;line-height:1.4;font-weight:650}' +
      '.vcs-input-wrap{position:relative}' +
      '.vcs-input-icon{position:absolute;top:50%;left:14px;width:18px;height:18px;transform:translateY(-50%);color:#8290a4;pointer-events:none}' +
      '.vcs-form input{appearance:none;display:block;width:100%;height:50px;margin:0;padding:0 14px 0 42px;border:1px solid #d5deeb;border-radius:11px;background:#f2f5fa;color:#172033;font:14px/1.4 system-ui,-apple-system,"Segoe UI",sans-serif;box-shadow:inset 0 1px 2px rgba(16,24,40,.035);transition:background .15s,border-color .15s,box-shadow .15s}' +
      '.vcs-form input::placeholder{color:#8995a7;opacity:1}' +
      '.vcs-form input:hover{border-color:#b8c5d6;background:#eef2f8}' +
      '.vcs-form input:focus{outline:none;border-color:#5575dc;background:#eaf0fc;box-shadow:0 0 0 4px rgba(79,102,210,.14)}' +
      '.vcs-submit{appearance:none;display:flex;align-items:center;justify-content:center;width:100%;min-height:46px;margin:12px 0 0;padding:12px 16px;border:0;border-radius:11px;background:linear-gradient(135deg,#3567df,#2855c7);color:#fff;font:700 14px/1.2 system-ui,-apple-system,"Segoe UI",sans-serif;box-shadow:0 7px 16px rgba(43,87,201,.2);cursor:pointer;transition:filter .15s,transform .15s,box-shadow .15s}' +
      '.vcs-submit:hover:not(:disabled){transform:translateY(-1px);filter:brightness(1.04);box-shadow:0 9px 20px rgba(43,87,201,.25)}' +
      '.vcs-submit:disabled{opacity:.65;cursor:wait}' +
      '.vcs-message{margin:13px 0 0;padding:11px 13px;border:1px solid #fed7aa;border-radius:10px;background:#fff7ed;color:#9a3412;font-size:13px;line-height:1.45}' +
      '.vcs-message[hidden]{display:none}' +
      '.vcs-result{margin-top:17px}.vcs-result:empty{display:none}' +
      '.vs-card{color:#e8eefc}' +
      '@media(max-width:520px){.vcs-dialog{width:100%;max-height:88vh;max-height:88dvh;padding:21px 19px calc(21px + env(safe-area-inset-bottom));border-radius:19px 19px 14px 14px}.vcs-title{font-size:19px}.vcs-description{margin-bottom:17px}}' +
      '</style>' +
      '<div class="vcs-backdrop" aria-hidden="true"></div>' +
      '<section class="vcs-dialog" role="dialog" aria-modal="true" aria-labelledby="vcs-title" aria-describedby="vcs-description">' +
      '<header class="vcs-header"><h2 class="vcs-title" id="vcs-title"></h2><button class="vcs-close" type="button" aria-label="Close dialog"><svg viewBox="0 0 20 20" width="18" height="18" aria-hidden="true"><path d="m5 5 10 10M15 5 5 15" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></button></header>' +
      '<p class="vcs-description" id="vcs-description">Enter the address associated with your record to view your latest status.</p>' +
      '<form class="vcs-form"><label for="vcs-address"></label><div class="vcs-input-wrap"><svg class="vcs-input-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 21s7-6.1 7-12a7 7 0 1 0-14 0c0 5.9 7 12 7 12Z" stroke="currentColor" stroke-width="1.7"/><circle cx="12" cy="9" r="2.3" stroke="currentColor" stroke-width="1.7"/></svg><input id="vcs-address" name="address" autocomplete="off" autocapitalize="off" spellcheck="false" placeholder="Paste or type your address" required></div><button class="vcs-submit" type="submit"></button></form>' +
      '<div class="vcs-message" role="status" aria-live="polite" hidden></div><div class="vcs-result"></div></section>';
    overlay.style.setProperty('all', 'initial', 'important');
    overlay.style.setProperty('position', 'fixed', 'important');
    overlay.style.setProperty('inset', '0', 'important');
    overlay.style.setProperty('width', '100vw', 'important');
    overlay.style.setProperty('height', '100vh', 'important');
    overlay.style.setProperty('height', '100dvh', 'important');
    overlay.style.setProperty('z-index', '2147483647', 'important');
    overlay.style.setProperty('box-sizing', 'border-box', 'important');
    overlay.style.setProperty('isolation', 'isolate', 'important');
    document.body.appendChild(overlay);

    var title = shadowRoot.querySelector('.vcs-title');
    var label = shadowRoot.querySelector('.vcs-form label');
    var input = shadowRoot.querySelector('.vcs-form input');
    var form = shadowRoot.querySelector('.vcs-form');
    var submit = shadowRoot.querySelector('.vcs-submit');
    var message = shadowRoot.querySelector('.vcs-message');
    var result = shadowRoot.querySelector('.vcs-result');
    var closeButton = shadowRoot.querySelector('.vcs-close');
    var backdrop = shadowRoot.querySelector('.vcs-backdrop');
    var configUrl = settings.configEndpoint || settings.endpoint.replace(/\/$/, '') + '/widget-config';
    var lastFocus = null;
    var destroyed = false;

    function showMessage(text) {
      message.textContent = text || 'Unable to check status right now. Please try again.';
      message.hidden = false;
    }

    function clearResult() {
      result.innerHTML = '';
      message.hidden = true;
      form.hidden = false;
      input.value = '';
    }

    function setOverlayLayout(isOpen) {
      var isSmallScreen = window.matchMedia('(max-width: 520px)').matches;
      overlay.style.setProperty('display', isOpen ? 'flex' : 'none', 'important');
      overlay.style.setProperty('align-items', isSmallScreen ? 'flex-end' : 'center', 'important');
      overlay.style.setProperty('justify-content', 'center', 'important');
      overlay.style.setProperty('padding', isSmallScreen ? '10px' : '16px', 'important');
      overlay.style.setProperty('background', 'transparent', 'important');
      overlay.style.setProperty('font', '15px/1.5 system-ui,-apple-system,"Segoe UI",sans-serif', 'important');
    }

    function handleResize() {
      if (overlay.getAttribute('aria-hidden') === 'false') setOverlayLayout(true);
    }

    setOverlayLayout(false);

    function open() {
      if (destroyed) return;
      clearResult();
      lastFocus = document.activeElement;
      setOverlayLayout(true);
      overlay.setAttribute('aria-hidden', 'false');
      input.focus();
      if (typeof settings.onOpen === 'function') settings.onOpen();
    }

    function close() {
      overlay.setAttribute('aria-hidden', 'true');
      setOverlayLayout(false);
      if (lastFocus && typeof lastFocus.focus === 'function') lastFocus.focus();
      if (typeof settings.onClose === 'function') settings.onClose();
    }

    function handleKeydown(event) {
      if (event.key === 'Escape' && overlay.getAttribute('aria-hidden') === 'false') close();
    }

    button.addEventListener('click', open);
    closeButton.addEventListener('click', close);
    backdrop.addEventListener('click', close);
    window.addEventListener('resize', handleResize);
    document.addEventListener('keydown', handleKeydown);

    form.addEventListener('submit', function (event) {
      event.preventDefault();
      if (!settings.tenantKey) {
        showMessage('This status widget is missing its tenant key. Contact the website administrator.');
        return;
      }
      var address = input.value.trim();
      if (!address) {
        showMessage('Please enter an address.');
        return;
      }

      message.hidden = true;
      result.innerHTML = '';
      submit.disabled = true;
      submit.textContent = settings.loadingText || 'Checking…';

      fetch(buildUrl(settings.endpoint, { address: address, tenant_key: settings.tenantKey }), {
        method: 'GET',
        headers: { 'Accept': 'application/json' },
        credentials: 'omit'
      }).then(function (response) {
        return response.json().then(function (data) {
          return { ok: response.ok, data: data };
        });
      }).then(function (response) {
        if (!response.ok || response.data.found !== true || typeof response.data.html !== 'string' || !response.data.html.trim()) {
          form.hidden = false;
          showMessage(response.data.message || 'No such record found for this address.');
          if (typeof settings.onResult === 'function') settings.onResult(response.data);
          return;
        }
        result.innerHTML = response.data.html;
        form.hidden = true;
        message.hidden = true;
        if (typeof settings.onResult === 'function') settings.onResult(response.data);
      }).catch(function (error) {
        form.hidden = false;
        showMessage('Could not reach the status service. Check the endpoint, network connection, and allowed website domains.');
        if (typeof settings.onError === 'function') settings.onError(error);
      }).finally(function () {
        submit.disabled = false;
        submit.textContent = settings.submitText || 'Check Status';
      });
    });

    title.textContent = settings.modalTitle || 'Check Status';
    label.textContent = settings.addressLabel || 'Enter your address';
    submit.textContent = settings.submitText || 'Check Status';

    var api = {
      open: open,
      close: close,
      destroy: function () {
        if (destroyed) return;
        destroyed = true;
        document.removeEventListener('keydown', handleKeydown);
        button.removeEventListener('click', open);
        window.removeEventListener('resize', handleResize);
        button.remove();
        overlay.remove();
        delete target.__victosStatusWidget;
      },
      element: button
    };
    target.__victosStatusWidget = api;

    if (settings.tenantKey && settings.loadConfig !== false) {
      fetch(buildUrl(configUrl, { tenant_key: settings.tenantKey }), {
        headers: { 'Accept': 'application/json' },
        credentials: 'omit'
      }).then(function (response) {
        if (!response.ok) throw new Error('Widget configuration unavailable');
        return response.json();
      }).then(function (config) {
        var btn = config.button || {};
        var modal = config.modal || {};
        if (!options.buttonText && btn.text) {
          button.textContent = btn.text;
          button.setAttribute('aria-label', btn.text);
        }
        if (!options.buttonTextColor && btn.textColor) button.style.color = btn.textColor;
        if (!options.buttonBackgroundColor && btn.backgroundColor) button.style.backgroundColor = btn.backgroundColor;
        if (!options.buttonFontSize && btn.fontSize) button.style.fontSize = btn.fontSize;
        if (!options.modalTitle && modal.title) title.textContent = modal.title;
        if (!options.addressLabel && modal.addressLabel) label.textContent = modal.addressLabel;
        if (!options.submitText && modal.submitText) submit.textContent = modal.submitText;
        if (!options.submitText && modal.submitText) settings.submitText = modal.submitText;
      }).catch(function (error) {
        if (typeof settings.onConfigError === 'function') settings.onConfigError(error);
      });
    }

    return api;
  }

  function init(options) {
    options = options || {};
    addStyles();
    var targets = resolveTargets(options.target);
    var instances = targets.map(function (target) { return mount(target, options); });
    return {
      instances: instances,
      open: function () { if (instances[0]) instances[0].open(); },
      close: function () { instances.forEach(function (instance) { instance.close(); }); },
      mount: function (target) {
        var elements = resolveTargets(target);
        elements.forEach(function (element) { instances.push(mount(element, options)); });
        return this;
      },
      destroy: function () {
        instances.forEach(function (instance) { instance.destroy(); });
        instances = [];
      }
    };
  }

  global.VictosCheckStatus = { init: init };
})(window);
