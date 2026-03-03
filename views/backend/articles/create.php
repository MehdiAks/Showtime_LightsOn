<!--
    /*
     * Vue d'administration (création) pour le module articles.
     * - Cette page expose un formulaire HTML complet permettant de saisir les données métier.
     * - L'action du formulaire pointe vers la route de création côté backend (controller/action).
     * - Les champs sont regroupés par sections pour guider l'utilisateur et faciliter la validation.
     * - Les boutons principaux déclenchent l'envoi et les liens secondaires ramènent au tableau de bord ou à la liste.
     * - Les classes Bootstrap structurent la mise en forme sans logique métier dans la vue.
     */
-->
<div class="article-editor-page">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1>Créer un article</h1>
            <p class="text-muted mb-0">
                Rédigez directement dans la mise en page finale : les champs restent invisibles pendant que
                l’article se met en forme au fur et à mesure.
            </p>
        </div>
        <button type="submit" form="article-create-form" class="btn btn-primary">Confirmer la création</button>
    </div>

    <form id="article-create-form" action="<?php echo ROOT_URL . '/public/index.php?controller=article&action=store'; ?>" method="post"
        enctype="multipart/form-data">
        <section class="article-page article-editor">
            <header class="article-hero" style="--hero-image: url('<?php echo ROOT_URL . '/src/images/article.png'; ?>')">
                <div class="article-hero__overlay">
                    <p class="article-kicker">Actualités</p>
                    <div class="article-editor-field article-editor-field--light">
                        <h1 id="preview-title" class="article-title article-editor-display article-editor-display--title"
                            data-placeholder="Titre de l’article"></h1>
                        <input id="libTitrArt" name="libTitrArt" class="article-editor-input article-editor-input--light"
                            type="text" maxlength="100" required data-preview-target="preview-title"
                            placeholder="Titre de l’article" />
                    </div>
                    <div class="article-meta">
                        <span>Publié le</span>
                        <span class="article-editor-field article-editor-field--light article-editor-field--inline">
                            <span id="preview-date" class="article-editor-display article-editor-display--meta"
                                data-placeholder="Date de publication"></span>
                            <input id="dtCreaArt" name="dtCreaArt"
                                class="article-editor-input article-editor-input--light" type="datetime-local" required
                                data-preview-target="preview-date" placeholder="JJ/MM/AAAA HH:MM" />
                        </span>
                        <span class="article-meta__dot">•</span>
                        <span>Lecture 2 min</span>
                    </div>
                </div>
            </header>

            <section class="article-body">
                <div class="container">
                    <div class="article-editor-field">
                        <p id="preview-chapo" class="article-lead article-editor-display article-editor-display--lead"
                            data-placeholder="Ajoutez le chapeau de l’article pour donner le ton."></p>
                        <textarea id="libChapoArt" name="libChapoArt" class="article-editor-input" maxlength="500"
                            required data-preview-target="preview-chapo"
                            placeholder="Ajoutez le chapeau de l’article pour donner le ton."></textarea>
                    </div>

                    <div class="row g-4">
                        <div class="col-12 col-lg-8">
                            <article class="bg-white">
                                <div class="article-editor-field">
                                    <h2 id="preview-accroche"
                                        class="phraseaccroche article-editor-display article-editor-display--accroche"
                                        data-placeholder="Ajoutez l’accroche principale."></h2>
                                    <input id="libAccrochArt" name="libAccrochArt" class="article-editor-input"
                                        type="text" maxlength="100" required data-preview-target="preview-accroche"
                                        placeholder="Accroche principale..." />
                                </div>

                                <div class="article-editor-field">
                                    <p id="preview-parag1"
                                        class="paragraphe article-editor-display article-editor-display--paragraph"
                                        data-placeholder="Premier paragraphe : racontez l’essentiel ici."></p>
                                    <textarea id="parag1Art" name="parag1Art" class="article-editor-input"
                                        maxlength="1200" required data-preview-target="preview-parag1"
                                        placeholder="Premier paragraphe : racontez l’essentiel ici."></textarea>
                                </div>

                                <figure class="article-figure article-editor-figure">
                                    <img class="image2 img-fluid w-100"
                                        src="<?php echo ROOT_URL . '/src/images/article.png'; ?>"
                                        alt="Image de l'article">
                                    <figcaption class="article-caption">
                                        © Groupe 1 Bordeaux étudiant club + Description de l’image
                                    </figcaption>
                                </figure>

                                <div class="article-editor-field">
                                    <div id="preview-subtitle1"
                                        class="text-with-line article-editor-display article-editor-display--subtitle"
                                        data-placeholder="Sous-titre 1"></div>
                                    <input id="libSsTitr1Art" name="libSsTitr1Art" class="article-editor-input"
                                        type="text" maxlength="100" required data-preview-target="preview-subtitle1"
                                        placeholder="Sous-titre 1" />
                                </div>

                                <div class="article-editor-field">
                                    <p id="preview-parag2"
                                        class="paragraphe2 article-editor-display article-editor-display--paragraph"
                                        data-placeholder="Deuxième paragraphe : développez votre idée."></p>
                                    <textarea id="parag2Art" name="parag2Art" class="article-editor-input"
                                        maxlength="1200" required data-preview-target="preview-parag2"
                                        placeholder="Deuxième paragraphe : développez votre idée."></textarea>
                                </div>

                                <div class="article-editor-field">
                                    <div id="preview-subtitle2"
                                        class="text-with-line article-editor-display article-editor-display--subtitle"
                                        data-placeholder="Sous-titre 2"></div>
                                    <input id="libSsTitr2Art" name="libSsTitr2Art" class="article-editor-input"
                                        type="text" maxlength="100" required data-preview-target="preview-subtitle2"
                                        placeholder="Sous-titre 2" />
                                </div>

                                <div class="article-editor-field">
                                    <p id="preview-parag3"
                                        class="paragraphe3 article-editor-display article-editor-display--paragraph"
                                        data-placeholder="Troisième paragraphe : concluez votre développement."></p>
                                    <textarea id="parag3Art" name="parag3Art" class="article-editor-input"
                                        maxlength="1200" required data-preview-target="preview-parag3"
                                        placeholder="Troisième paragraphe : concluez votre développement."></textarea>
                                </div>

                                <div class="article-editor-field">
                                    <p id="preview-concl"
                                        class="conclusion article-editor-display article-editor-display--conclusion"
                                        data-placeholder="Conclusion : terminez sur une note forte."></p>
                                    <textarea id="libConclArt" name="libConclArt" class="article-editor-input"
                                        maxlength="800" required data-preview-target="preview-concl"
                                        placeholder="Conclusion : terminez sur une note forte."></textarea>
                                </div>
                            </article>
                        </div>

                        <aside class="col-12 col-lg-4 article-editor__panel">
                            <div class="card shadow-sm mb-4">
                                <div class="card-body">
                                    <h2 class="h5 mb-3">Paramètres de publication</h2>
                                    <div class="mb-3">
                                        <label for="urlPhotArt" class="form-label">Choisir une image</label>
                                        <input type="file" id="urlPhotArt" name="urlPhotArt" class="form-control"
                                            accept=".png, .jpeg, .jpg, .avif, .svg" maxlength="80000" width="80000"
                                            height="80000" size="200000000000">
                                        <p class="form-text">Extensions acceptées : .png, .jpeg, .jpg, .avif, .svg.</p>
                                    </div>

                                    <div class="mb-3">
                                        <label for="numThem" class="form-label">Thématique</label>
                                        <select id="numThem" name="numThem" class="form-select" required>
                                            <option value="">-- Choisissez une thématique --</option>
                                            <?php foreach ($ba_bec_thematiques as $ba_bec_thematique) { ?>
                                                <option value="<?= $ba_bec_thematique['numThem'] ?>">
                                                    <?= $ba_bec_thematique['libThem'] ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Mots-clés liés à l'article</label>
                                        <div class="row g-2">
                                            <div class="col-12">
                                                <select name="addMotCle" id="addMotCle" class="form-select" size="5">
                                                    <?php foreach ($ba_bec_keywords as $ba_bec_req) { ?>
                                                        <option id="mot" value="<?php echo $ba_bec_req['numMotCle']; ?>">
                                                            <?php echo $ba_bec_req['libMotCle']; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <select id="newMotCle" name="motCle[]" class="form-select" size="5"
                                                    multiple></select>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">Confirmer la création</button>
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
            </section>
        </section>
    </form>
</div>

<script>
    const addMotCle = document.getElementById('addMotCle');
    const newMotCle = document.getElementById('newMotCle');
    const newOptions = newMotCle?.options;

    if (addMotCle && newMotCle) {
        addMotCle.addEventListener('click', (e) => {
            if (e.target.tagName !== "OPTION") {
                return;
            }
            e.target.setAttribute('selected', true);
            newMotCle.appendChild(e.target);
        });

        newMotCle.addEventListener('click', (e) => {
            if (e.target.tagName !== "OPTION") {
                return;
            }
            e.stopPropagation();
            e.preventDefault();
            e.stopImmediatePropagation();
            e.target.setAttribute('selected', false);
            addMotCle.appendChild(e.target);
            for (let option of newMotCle.children) {
                option.setAttribute('selected', true);
            }
        });
    }

    const formatDateTime = (value) => {
        if (!value) {
            return '';
        }
        const date = new Date(value);
        if (Number.isNaN(date.getTime())) {
            return value;
        }
        const datePart = date.toLocaleDateString('fr-FR');
        const timePart = date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
        return `${datePart} ${timePart}`;
    };

    const updatePreview = (input, target) => {
        const placeholder = target.dataset.placeholder || '';
        const rawValue = input.value.trim();
        const formattedValue = input.type === 'datetime-local' ? formatDateTime(rawValue) : rawValue;
        const nextValue = formattedValue || placeholder;

        target.textContent = nextValue;
        target.classList.toggle('is-placeholder', !formattedValue);
    };

    document.querySelectorAll('[data-preview-target]').forEach((input) => {
        const target = document.getElementById(input.dataset.previewTarget);
        if (!target) {
            return;
        }
        const handler = () => updatePreview(input, target);
        input.addEventListener('input', handler);
        input.addEventListener('change', handler);
        handler();
    });
</script>
