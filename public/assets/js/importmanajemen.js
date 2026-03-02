  const $ = (sel) => document.querySelector(sel);

    function toast(msg){
      const el = $("#toast");
      el.textContent = msg;
      el.style.display = "block";
      clearTimeout(toast._t);
      toast._t = setTimeout(()=> el.style.display="none", 2600);
    }

    function downloadText(filename, text){
      const blob = new Blob([text], {type: "text/csv;charset=utf-8"});
      const url = URL.createObjectURL(blob);
      const a = document.createElement("a");
      a.href = url;
      a.download = filename;
      document.body.appendChild(a);
      a.click();
      a.remove();
      URL.revokeObjectURL(url);
    }

    // CSV parsing minimal (supports quoted fields)
    function parseCSV(text){
      const rows = [];
      let i = 0, field = "", row = [], inQuotes = false;

      while (i < text.length){
        const c = text[i];

        if (inQuotes){
          if (c === '"'){
            const next = text[i+1];
            if (next === '"'){ field += '"'; i += 2; continue; } // escaped quote
            inQuotes = false; i++; continue;
          } else {
            field += c; i++; continue;
          }
        } else {
          if (c === '"'){ inQuotes = true; i++; continue; }
          if (c === ","){ row.push(field); field = ""; i++; continue; }
          if (c === "\n"){
            row.push(field); field = "";
            // ignore empty trailing row
            if (!(row.length === 1 && row[0].trim() === "")) rows.push(row);
            row = []; i++; continue;
          }
          if (c === "\r"){ i++; continue; }
          field += c; i++; continue;
        }
      }

      // last line
      if (field.length || row.length){
        row.push(field);
        if (!(row.length === 1 && row[0].trim() === "")) rows.push(row);
      }
      return rows;
    }

    function normalizeHeader(h){
      return (h || "").trim().toLowerCase();
    }

    function isEmail(v){
      // Basic email check (not RFC complete)
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test((v || "").trim());
    }

    function onlyDigits(v){
      return (v || "").replace(/\D/g, "");
    }

    // =========================
    // TEMPLATE DOWNLOAD
    // =========================
    $("#btnDownload").addEventListener("click", () => {
      const csv =
`nama,email,telepon
Budi Santoso,budi@example.com,081234567890
Siti Aisyah,siti@example.com,082233445566
`;
      downloadText("template_import_peserta.csv", csv);
      toast("Template diunduh.");
    });

    // =========================
    // IMPORT FLOW
    // =========================
    const dropzone = $("#dropzone");
    const fileInput = $("#fileInput");
    const fileName = $("#fileName");
    const btnSave = $("#btnSave");
    const preview = $("#preview");
    const previewTable = $("#previewTable");

    let parsed = { headers: [], rows: [], valid: [], invalid: [] };

    function resetState(){
      parsed = { headers: [], rows: [], valid: [], invalid: [] };
      btnSave.disabled = true;
      preview.style.display = "none";
      previewTable.innerHTML = "";
      $("#metaMsg").textContent = "";
      $("#metaRows").textContent = "0 baris";
      $("#metaValid").textContent = "0 valid";
      $("#metaInvalid").textContent = "0 invalid";
    }

    function setFile(f){
      if (!f) return;
      if (!/\.csv$/i.test(f.name)){
        toast("File harus .csv");
        return;
      }
      fileName.textContent = f.name;
      readFile(f);
    }

    async function readFile(file){
      resetState();
      const text = await file.text();

      const rows = parseCSV(text);
      if (!rows.length){
        toast("CSV kosong.");
        return;
      }

      const headers = rows[0].map(normalizeHeader);
      const dataRows = rows.slice(1);

      const idxNama = headers.indexOf("nama");
      const idxEmail = headers.indexOf("email");
      const idxTelp = headers.indexOf("telepon");

      parsed.headers = headers;
      parsed.rows = dataRows;

      // minimal required columns
      const missing = [];
      if (idxNama === -1) missing.push("nama");
      if (idxEmail === -1) missing.push("email");
      if (idxTelp === -1) missing.push("telepon");

      if (missing.length){
        $("#metaMsg").textContent = "Header kurang: " + missing.join(", ");
        toast("Header CSV wajib: nama,email,telepon");
        renderPreview(headers, dataRows, [], dataRows.map((r, k) => ({i:k, row:r, err:"Header wajib tidak lengkap"})));
        preview.style.display = "block";
        return;
      }

      // validate rows
      const valid = [];
      const invalid = [];

      dataRows.forEach((r, i) => {
        const nama = (r[idxNama] || "").trim();
        const email = (r[idxEmail] || "").trim();
        const telpRaw = (r[idxTelp] || "").trim();
        const telp = onlyDigits(telpRaw);

        const errs = [];
        if (!nama) errs.push("nama kosong");
        if (!isEmail(email)) errs.push("email tidak valid");
        if (telp.length < 8) errs.push("telepon kurang dari 8 digit");

        if (errs.length){
          invalid.push({ i, row: r, err: errs.join("; ") });
        } else {
          valid.push({ i, row: r, data: { nama, email, telepon: telp } });
        }
      });

      parsed.valid = valid;
      parsed.invalid = invalid;

      $("#metaRows").textContent = `${dataRows.length} baris`;
      $("#metaValid").textContent = `${valid.length} valid`;
      $("#metaInvalid").textContent = `${invalid.length} invalid`;
      $("#metaMsg").textContent = invalid.length ? "Perbaiki baris invalid sebelum simpan." : "Siap disimpan.";
      btnSave.disabled = valid.length === 0 || invalid.length > 0;

      renderPreview(headers, dataRows, valid, invalid);
      preview.style.display = "block";
    }

    function renderPreview(headers, dataRows, valid, invalid){
      // Build preview table with first N rows (and show error column)
      const N = Math.min(10, dataRows.length);
      const hasErrorCol = true;

      const headerCells = headers.map(h => `<th>${escapeHTML(h || "(kosong)")}</th>`).join("");
      const th = `<tr>${headerCells}${hasErrorCol ? "<th>status</th>" : ""}</tr>`;

      const invalidMap = new Map(invalid.map(x => [x.i, x.err]));
      const validSet = new Set(valid.map(x => x.i));

      let body = "";
      for (let i = 0; i < N; i++){
        const r = dataRows[i] || [];
        const tds = headers.map((_, cIdx) => `<td>${escapeHTML(r[cIdx] ?? "")}</td>`).join("");
        let status = "";
        if (invalidMap.has(i)) status = `❌ ${escapeHTML(invalidMap.get(i))}`;
        else if (validSet.has(i)) status = "✅ valid";
        else status = "—";
        body += `<tr>${tds}<td>${status}</td></tr>`;
      }

      previewTable.innerHTML = `<thead>${th}</thead><tbody>${body}</tbody>`;
    }

    function escapeHTML(s){
      return String(s).replace(/[&<>"']/g, (m) => ({
        "&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#039;"
      }[m]));
    }

    // Drag & drop handlers
    ["dragenter","dragover"].forEach(evt => {
      dropzone.addEventListener(evt, (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropzone.classList.add("dragover");
      });
    });
    ["dragleave","drop"].forEach(evt => {
      dropzone.addEventListener(evt, (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropzone.classList.remove("dragover");
      });
    });
    dropzone.addEventListener("drop", (e) => {
      const f = e.dataTransfer.files && e.dataTransfer.files[0];
      if (f) setFile(f);
    });

    // Click dropzone -> open file picker
    dropzone.addEventListener("click", () => fileInput.click());
    dropzone.addEventListener("keydown", (e) => {
      if (e.key === "Enter" || e.key === " ") fileInput.click();
    });

    // File input change
    fileInput.addEventListener("change", (e) => {
      const f = e.target.files && e.target.files[0];
      if (f) setFile(f);
    });

    // Save (simulate API call)
    btnSave.addEventListener("click", async () => {
      if (btnSave.disabled) return;

      // Payload siap kirim ke backend
      const payload = parsed.valid.map(v => v.data);

      // Contoh: kirim ke endpoint Anda
      // Ganti URL sesuai kebutuhan. Jika tidak ada backend, ini hanya simulasi.
      try{
        btnSave.disabled = true;
        $("#metaMsg").textContent = "Menyimpan...";
        // Simulasi delay
        await new Promise(r => setTimeout(r, 800));

        // Jika punya backend:
        // const res = await fetch("/api/peserta/import", {
        //   method: "POST",
        //   headers: { "Content-Type":"application/json" },
        //   body: JSON.stringify({ peserta: payload })
        // });
        // if(!res.ok) throw new Error("Gagal simpan");

        $("#metaMsg").textContent = "Tersimpan.";
        toast(`Berhasil simpan ${payload.length} peserta.`);
      } catch (err){
        $("#metaMsg").textContent = "Gagal menyimpan.";
        toast("Gagal menyimpan. Periksa endpoint/API.");
      } finally{
        // re-enable jika masih valid & tidak ada invalid
        btnSave.disabled = !(parsed.valid.length > 0 && parsed.invalid.length === 0);
      }

      // Untuk debugging:
      console.log("IMPORT_PAYLOAD", payload);
    });