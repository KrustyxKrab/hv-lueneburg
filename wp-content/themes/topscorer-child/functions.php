<?php

if ( ! function_exists( 'topscorer_child_theme_enqueue_scripts' ) ) {
	/**
	 * Function that enqueue theme's child style
	 */
	function topscorer_child_theme_enqueue_scripts() {
		$main_style = 'topscorer-main';
		
		wp_enqueue_style( 'topscorer-child-style', get_stylesheet_directory_uri() . '/style.css', array( $main_style ) );
	}
	
	add_action( 'wp_enqueue_scripts', 'topscorer_child_theme_enqueue_scripts' );
}

/**
 * Main handball shortcode with custom styling option
 */
function hvl_handball_shortcode($atts) {
    $atts = shortcode_atts(array(
        'club' => 'nuliga.hvn.681',
        'container' => '',
        'widget' => 'spielplan',
        'custom_style' => 'false'
    ), $atts);

    // Generate unique container ID
    if (empty($atts['container'])) {
        $container_id = 'handball-' . $atts['widget'] . '-' . uniqid();
    } else {
        $container_id = $atts['container'] . '-' . uniqid();
    }
    
    $custom_style = ($atts['custom_style'] === 'true');
    
    if ($custom_style) {
        return hvl_render_custom_widget($atts['club'], $atts['widget'], $container_id);
    } else {
        return hvl_render_normal_widget($atts['club'], $atts['widget'], $container_id);
    }
}
add_shortcode('hvl_handball', 'hvl_handball_shortcode');

/**
 * Normal widget rendering with error detection
 */
function hvl_render_normal_widget($club, $widget, $container_id) {
    ob_start();
    ?>
    <div id="<?php echo esc_attr($container_id); ?>"></div>
    <script>
    _hb({
        widget: '<?php echo esc_js($widget); ?>',
        clubId: '<?php echo esc_js($club); ?>',
        container: '<?php echo esc_js($container_id); ?>'
    });
    
    // Check for widget loading errors after 5 seconds
    setTimeout(function() {
        hvlCheckWidgetError('<?php echo esc_js($container_id); ?>', '<?php echo esc_js($club); ?>', '<?php echo esc_js($widget); ?>');
    }, 5000);
    
    function hvlCheckWidgetError(containerId, clubId, widgetType) {
        var container = document.getElementById(containerId);
        if (!container) return;
        
        var hasContent = container.children.length > 0 && container.textContent.trim().length > 0;
        
        if (!hasContent) {
            var errorMsg = hvlGetErrorMessage(clubId, widgetType);
            container.innerHTML = '<div class="hvl-error-box">' + errorMsg + '</div>';
        }
    }
    
    function hvlGetErrorMessage(clubId, widgetType) {
        var isClubId = clubId.indexOf('.') > -1; // Club IDs typically contain dots
        var teamSpecificWidgets = ['spielplan'];
        var clubSpecificWidgets = ['vereinsplan', 'ligaplan'];
        var isTeamWidget = teamSpecificWidgets.indexOf(widgetType) > -1;
        var isClubWidget = clubSpecificWidgets.indexOf(widgetType) > -1;
        
        if (isTeamWidget && isClubId) {
            return '<strong>Fehler:</strong> Widget-Typ "' + widgetType + '" benötigt eine Team-ID, aber Club-ID "' + clubId + '" wurde angegeben.<br>' +
                   '<small>Tipp: Verwenden Sie eine spezifische Team-ID oder wechseln Sie zu "vereinsplan" für Club-IDs.</small>';
        }
        
        if (isClubWidget && !isClubId) {
            return '<strong>Fehler:</strong> Widget-Typ "' + widgetType + '" benötigt eine Club-ID, aber Team-ID "' + clubId + '" wurde angegeben.<br>' +
                   '<small>Tipp: Verwenden Sie eine Club-ID (z.B. nuliga.hvn.681) oder wechseln Sie zu "spielplan" für Team-IDs.</small>';
        }
        
        return '<strong>Fehler:</strong> Widget konnte nicht geladen werden.<br>' +
               '<small>Überprüfen Sie die Club-/Team-ID "' + clubId + '" und den Widget-Typ "' + widgetType + '".</small>';
    }
    </script>
    
    <style>
    .hvl-error-box {
        background: #ffebee;
        border: 1px solid #f44336;
        border-radius: 4px;
        padding: 15px;
        margin: 10px 0;
        color: #d32f2f;
        font-size: 14px;
        line-height: 1.4;
    }
    .hvl-error-box strong {
        display: block;
        margin-bottom: 5px;
    }
    .hvl-error-box small {
        color: #666;
        font-size: 12px;
    }
    </style>
    <?php
    return ob_get_clean();
}

/**
 * Custom styled widget rendering with error detection
 */
function hvl_render_custom_widget($club, $widget, $container_id) {
    $hidden_id = $container_id . '_hidden';
    $custom_id = $container_id . '_custom';
    
    ob_start();
    ?>
    <!-- Hidden source widget -->
    <div id="<?php echo esc_attr($hidden_id); ?>" style="position: absolute; left: -9999px; visibility: hidden;"></div>
    
    <!-- Custom styled display -->
    <div id="<?php echo esc_attr($custom_id); ?>" class="hvl-custom-<?php echo esc_attr($widget); ?>">
        <div class="hvl-custom-loading">Daten werden geladen...</div>
    </div>

    <!-- Initialize hidden widget -->
    <script>
    _hb({
        widget: '<?php echo esc_js($widget); ?>',
        clubId: '<?php echo esc_js($club); ?>',
        container: '<?php echo esc_js($hidden_id); ?>'
    });
    
    // Extract and style data after 3 seconds
    setTimeout(function() {
        hvlExtractData('<?php echo esc_js($hidden_id); ?>', '<?php echo esc_js($custom_id); ?>', '<?php echo esc_js($widget); ?>', '<?php echo esc_js($club); ?>');
    }, 3000);
    
    function hvlExtractData(sourceId, targetId, widgetType, clubId) {
        var source = document.getElementById(sourceId);
        var target = document.getElementById(targetId);
        
        if (!source || !target) return;
        
        var content = source.innerHTML;
        var hasContent = content && content.trim() !== '' && content.length > 50;
        
        if (hasContent) {
            var customHtml = hvlTransformData(content, widgetType);
            target.innerHTML = customHtml;
        } else {
            // Show error message
            var errorMsg = hvlGetErrorMessage(clubId, widgetType);
            target.innerHTML = '<div class="hvl-custom-error">' + errorMsg + '</div>';
        }
    }
    
    function hvlGetErrorMessage(clubId, widgetType) {
        var isClubId = clubId.indexOf('.') > -1;
        var teamSpecificWidgets = ['spielplan'];
        var clubSpecificWidgets = ['vereinsplan', 'ligaplan'];
        var isTeamWidget = teamSpecificWidgets.indexOf(widgetType) > -1;
        var isClubWidget = clubSpecificWidgets.indexOf(widgetType) > -1;
        
        if (isTeamWidget && isClubId) {
            return '<div class="hvl-error-header"><strong>ID-Konflikt erkannt</strong></div>' +
                   '<p>Das Widget "' + widgetType + '" erwartet eine <strong>Team-ID</strong>, aber Sie haben eine <strong>Club-ID</strong> angegeben: <code>' + clubId + '</code></p>' +
                   '<div class="hvl-error-solution">' +
                   '<strong>Lösungsvorschläge:</strong><br>' +
                   '• Verwenden Sie eine spezifische Team-ID für dieses Widget<br>' +
                   '• Oder wechseln Sie zu <code>widget="vereinsplan"</code> für Club-IDs' +
                   '</div>';
        }
        
        if (isClubWidget && !isClubId) {
            return '<div class="hvl-error-header"><strong>ID-Konflikt erkannt</strong></div>' +
                   '<p>Das Widget "' + widgetType + '" erwartet eine <strong>Club-ID</strong>, aber Sie haben eine <strong>Team-ID</strong> angegeben: <code>' + clubId + '</code></p>' +
                   '<div class="hvl-error-solution">' +
                   '<strong>Lösungsvorschläge:</strong><br>' +
                   '• Verwenden Sie eine Club-ID (z.B. nuliga.hvn.681)<br>' +
                   '• Oder wechseln Sie zu <code>widget="spielplan"</code> für Team-IDs' +
                   '</div>';
        }
        
        return '<div class="hvl-error-header"><strong>Widget-Fehler</strong></div>' +
               '<p>Das Widget konnte nicht geladen werden. Mögliche Ursachen:</p>' +
               '<ul>' +
               '<li>Ungültige ID: <code>' + clubId + '</code></li>' +
               '<li>Inkompatible Kombination: Widget "' + widgetType + '"</li>' +
               '<li>Netzwerk- oder Server-Problem</li>' +
               '</ul>' +
               '<div class="hvl-error-solution">Überprüfen Sie die ID auf handball.net</div>';
    }
    
    function hvlTransformData(html, widgetType) {
        var tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        
        if (widgetType === 'spielplan') {
            return hvlTransformSpielplan(tempDiv);
        } else if (widgetType === 'tabelle') {
            return hvlTransformTabelle(tempDiv);
        }
        
        return '<div class="hvl-fallback">' + html + '</div>';
    }
    
    function hvlTransformSpielplan(container) {
        var games = container.querySelectorAll('tr, .match, .game, [class*="spiel"]');
        var html = '<div class="hvl-header"><h3>Spielplan</h3></div><div class="hvl-games">';
        var hasGames = false;

        games.forEach(function(game) {
            var cells = game.querySelectorAll('td, .data, [class*="team"]');
            if (cells.length >= 2) {
                hasGames = true;
                var gameHtml = '<div class="hvl-game">';
                
                for (var i = 0; i < cells.length; i++) {
                    var text = cells[i].textContent.trim();
                    if (text) {
                        if (i === 0) gameHtml += '<div class="hvl-date">' + text + '</div>';
                        else if (i === 1) gameHtml += '<div class="hvl-teams">' + text + '</div>';
                        else if (i === 2) gameHtml += '<div class="hvl-result">' + text + '</div>';
                    }
                }
                
                gameHtml += '</div>';
                html += gameHtml;
            }
        });

        if (!hasGames) {
            html += '<div class="hvl-no-data">Keine Spiele gefunden</div>';
        }

        html += '</div>';
        return html;
    }
    
    function hvlTransformTabelle(container) {
        var rows = container.querySelectorAll('tr');
        var html = '<div class="hvl-header"><h3>Tabelle</h3></div><div class="hvl-table">';
        var hasRows = false;

        rows.forEach(function(row) {
            var cells = row.querySelectorAll('td');
            if (cells.length >= 2) {
                hasRows = true;
                var rowHtml = '<div class="hvl-table-row">';
                
                for (var i = 0; i < Math.min(cells.length, 4); i++) {
                    var text = cells[i].textContent.trim();
                    if (text) {
                        if (i === 0) rowHtml += '<div class="hvl-pos">' + text + '</div>';
                        else if (i === 1) rowHtml += '<div class="hvl-team">' + text + '</div>';
                        else if (i === 2) rowHtml += '<div class="hvl-points">' + text + '</div>';
                    }
                }
                
                rowHtml += '</div>';
                html += rowHtml;
            }
        });

        if (!hasRows) {
            html += '<div class="hvl-no-data">Keine Tabellendaten gefunden</div>';
        }

        html += '</div>';
        return html;
    }
    </script>

    <style>
    .hvl-custom-spielplan, .hvl-custom-tabelle {
        background: #ffffff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin: 20px 0;
    }

    .hvl-header h3 {
        margin: 0 0 20px 0;
        color: #2c3e50;
        border-bottom: 3px solid #3498db;
        padding-bottom: 8px;
        font-size: 1.3em;
    }

    .hvl-games, .hvl-table {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .hvl-game, .hvl-table-row {
        background: #f8f9fa;
        border-radius: 6px;
        padding: 15px;
        transition: all 0.2s ease;
        border-left: 4px solid transparent;
    }

    .hvl-game:hover, .hvl-table-row:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border-left-color: #3498db;
    }

    .hvl-date, .hvl-teams, .hvl-result {
        margin: 5px 0;
    }

    .hvl-date {
        font-size: 0.9em;
        color: #7f8c8d;
    }

    .hvl-teams {
        font-weight: 600;
        text-align: center;
    }

    .hvl-result {
        text-align: center;
        font-weight: bold;
        color: #3498db;
    }

    .hvl-table-row {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .hvl-pos {
        background: #3498db;
        color: white;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.9em;
    }

    .hvl-team {
        flex: 1;
        font-weight: 600;
    }

    .hvl-points {
        font-weight: 500;
        color: #2c3e50;
    }

    .hvl-custom-loading {
        text-align: center;
        padding: 20px;
        color: #7f8c8d;
        font-style: italic;
    }

    .hvl-no-data {
        text-align: center;
        color: #95a5a6;
        padding: 20px;
    }

    .hvl-fallback {
        font-size: 0.9em;
    }

    .hvl-custom-error {
        background: #ffebee;
        border: 1px solid #f44336;
        border-radius: 8px;
        padding: 20px;
        color: #d32f2f;
        font-size: 14px;
        line-height: 1.5;
    }

    .hvl-error-header {
        color: #c62828;
        font-size: 16px;
        margin-bottom: 10px;
    }

    .hvl-error-solution {
        background: #fff3e0;
        border: 1px solid #ff9800;
        border-radius: 4px;
        padding: 10px;
        margin-top: 15px;
        color: #e65100;
        font-size: 13px;
    }

    .hvl-custom-error code {
        background: #f5f5f5;
        border: 1px solid #ddd;
        border-radius: 3px;
        padding: 2px 4px;
        font-family: monospace;
        font-size: 12px;
    }

    .hvl-custom-error ul {
        margin: 10px 0;
        padding-left: 20px;
    }

    .hvl-custom-error li {
        margin: 5px 0;
    }

    @media (max-width: 768px) {
        .hvl-custom-spielplan, .hvl-custom-tabelle {
            padding: 15px;
        }
        
        .hvl-table-row {
            flex-wrap: wrap;
            gap: 10px;
        }
    }
    </style>
    <?php
    return ob_get_clean();
}

/**
 * Convenience shortcode for spielplan
 */
function hvl_spielplan_shortcode($atts) {
    $atts['widget'] = 'spielplan';
    return hvl_handball_shortcode($atts);
}
add_shortcode('hvl_spielplan', 'hvl_spielplan_shortcode');

/**
 * Table shortcode
 */
function hvl_tabelle_shortcode($atts) {
    $atts['widget'] = 'tabelle';
    return hvl_spielplan_shortcode($atts);
}
add_shortcode('hvl_tabelle', 'hvl_tabelle_shortcode');

/**
 * Load the handball.net script - exactly as their documentation shows
 */
function hvl_enqueue_handball_script() {
    ?>
    <script>
    (function(e,t,n,r,i,s,o){e[i]=e[i]||function(){(e[i].q=e[i].q||[]).push(arguments)}, e[i].l=1*new Date;s=t.createElement(n),o=t.getElementsByTagName(n)[0];s.async=1; s.src=r;o.parentNode.insertBefore(s,o)})(window,document,"script", 'https://www.handball.net/widgets/embed/v1.js',"_hb");
    </script>
    <?php
}
add_action('wp_head', 'hvl_enqueue_handball_script');