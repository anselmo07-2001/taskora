<?php

function renderPagination(array $config):string {
    var_dump($config);
    $currentPaginationPage = $config["currentPaginationPage"] ?? 1;
    $baseUrl = $config["baseUrl"] ?? [];
    $paginationStart = $config["paginationStart"] ?? 1;
    $paginationEnd = $config["paginationEnd"] ?? 1;
    $totalPages = $config["totalPages"] ?? 1;
    $page = $config["page"] ?? "";

    ob_start();
?>

<div class="d-flex justify-content-end">
    <nav aria-label="Page navigation">
        <ul class="pagination">
        
            <?php if ($currentPaginationPage > 1): ?>
                <li class="page-item">
                <a class="page-link" href="<?= BASE_URL . "/index.php?" . http_build_query(["page" => $page, "currentPaginationPage" => $currentPaginationPage - 1] + $baseUrl); ?>" >Previous</a>
            </li>
            <?php else: ?>
                <li class="page-item disabled">
                    <span class="page-link">Previous</span>
                </li>
            <?php endif; ?>
    
            <?php for ($i = $paginationStart; $i <= $paginationEnd; $i++): ?>
                <li class="page-item <?= $i == $currentPaginationPage ? 'active' : ''; ?>">
                    <a class="page-link <?= $i == $currentPaginationPage ? 'page-link-mycolor' : ''; ?>" 
                        href="<?=  BASE_URL . "/index.php?" . http_build_query(["page" => $page,  "currentPaginationPage" => $i] + $baseUrl) ?>">
                            <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>

            
            <?php if ($currentPaginationPage < $totalPages): ?>
            <li class="page-item">
                    <a class="page-link" href="<?= BASE_URL . "/index.php?" . http_build_query(["page" => $page, "currentPaginationPage" => $currentPaginationPage + 1] + $baseUrl); ?>">Next</a>
                </li>
            <?php else: ?>
                <li class="page-item disabled">
                    <span class="page-link">Next</span>
                </li>
            <?php endif; ?>
    
        </ul>
    </nav>
</div>

    <?php return ob_get_clean();     
}