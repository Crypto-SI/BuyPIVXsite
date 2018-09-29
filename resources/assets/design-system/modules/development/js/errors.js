window.onerror = (m,u,l,c,e) => (alert(`Exception: ${m} @ ${u} line ${l} column ${c}\n${e ? e.stack : ''}`), true);
