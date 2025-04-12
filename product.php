<?php
session_start();
include('db.php');
include('navbar.php');
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
$query = isset($_SESSION['user_id']) ?
    "SELECT 
        p.product_id, 
        p.product_name, 
        p.product_desc, 
        p.quantity, 
        p.health_benefits, 
        p.product_price, 
        p.product_likes, 
        p.product_type, 
        b.business_name, 
        a.area_name,
        IFNULL(v.user_id, 0) AS liked 
     FROM product p
     JOIN business b ON p.business_id = b.business_id
     JOIN area a ON b.area_id = a.area_id
     LEFT JOIN vote v ON p.product_id = v.product_id AND v.user_id = $user_id"
    :
    "SELECT 
        p.product_id, 
        p.product_name, 
        p.product_desc, 
        p.quantity, 
        p.health_benefits, 
        p.product_price, 
        p.product_likes, 
        p.product_type, 
        b.business_name, 
        a.area_name
     FROM product p
     JOIN business b ON p.business_id = b.business_id
     JOIN area a ON b.area_id = a.area_id";


$result = mysqli_query($conn, $query);

$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}
?>

<button id="backToTop" class="btn btn-primary position-fixed p-2 px-4" style="bottom: 40px; right: 40px; display: none; z-index: 1000;">
    ↑ Back to Top
</button>
<div style="margin:auto; max-width: 1800px; ">

    <div class="m-auto d-flex flex-column justify-content-center align-items-center p-5" style="width: 95%;">
        <nav class="card d-flex flex-row justify-content-center align-items-center w-100 gap-3 p-3 position-relative shadow p-4">
            <input type="text" id="searchBar" class="form-control w-25" placeholder="Search product...">
            <ul id="suggestions" class="list-group position-absolute" style="z-index: 999; top: 60px; left: 0; width: 25%; display: none;"></ul>

            <select id="priceFilter" class="form-control w-25">
                <option value="">All Prices</option>
                <option value="Affordable">Affordable</option>
                <option value="Moderate">Moderate</option>
                <option value="Premium">Premium</option>
            </select>

            <select id="areaFilter" class="form-control w-25">
                <option value="">All Areas</option>
                <?php
                $areas = mysqli_query($conn, "SELECT * FROM area");
                while ($a = mysqli_fetch_assoc($areas)) {
                    echo "<option value='" . $a['area_name'] . "'>" . $a['area_name'] . "</option>";
                }
                ?>
            </select>

            <select id="businessFilter" class="form-control w-25">
                <option value="">All Businesses</option>
                <?php
                $biz = mysqli_query($conn, "SELECT * FROM business");
                while ($b = mysqli_fetch_assoc($biz)) {
                    echo "<option value='" . $b['business_name'] . "'>" . $b['business_name'] . "</option>";
                }
                ?>
            </select>
        </nav>

        <div id="productContainer" class="d-grid gap-5 mt-5" style="grid-template-columns: repeat(4, 1fr); width: 100%;">
            <!-- Products will be rendered here -->
        </div>
    </div>
</div>
<script>
    //Back to top BTN
    const backToTopBtn = document.getElementById("backToTop");
    window.addEventListener("scroll", () => {
        if (window.scrollY > 300) {
            backToTopBtn.style.display = "block";
        } else {
            backToTopBtn.style.display = "none";
        }
    });

    backToTopBtn.addEventListener("click", () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    const products = <?php echo json_encode($products); ?>;
    const userId = <?php echo $user_id ? $user_id : 0; ?>;
    const userRole = "<?php echo $role; ?>";

    const productContainer = document.getElementById("productContainer");
    const priceFilter = document.getElementById("priceFilter");
    const areaFilter = document.getElementById("areaFilter");
    const businessFilter = document.getElementById("businessFilter");
    const searchInput = document.getElementById("searchBar");
    const suggestionsBox = document.getElementById("suggestions");

    function renderProducts(data) {
        productContainer.innerHTML = "";
        if (data.length === 0) {
            productContainer.innerHTML = "<p>No products found.</p>";
            return;
        }

        data.forEach(product => {
            const liked = product.liked == userId;
            const card = document.createElement("div");
            card.className = "card w-100 position-relative overflow-hidden";

            card.innerHTML = `
              <span class="position-absolute text-capitalize p-2 px-5 fs-6 badge bg-danger" style="top: 22px; right: -40px;">
                 ${product.product_type}
            </span>
            <img class="card-img-top" src="./assets/product.jpg" alt="Product image">
            <div class="card-body">
                <h5 class="card-title">${product.product_name}</h5>
                <p class="card-text">${product.product_desc}</p>
                <p class="card-text"><strong>Area:</strong> ${product.area_name}</p>
                <p class="card-text"><strong>Business:</strong> ${product.business_name}</p>
                <p class="card-text"><strong>Price:</strong> ${product.product_price}</p>
                <p class="card-text"><strong>Votes:</strong> ${product.product_likes}</p>
                <div class="d-flex justify-content-between align-items.center ">
                <a class="btn btn-primary w-50" href="/healthy_habitat/readmore.php?product_id=${product.product_id}">Read More</a>


                ${ userId != 0 ? (
                    liked ? `
                    <a href='/healthy_habitat/dislike.php?product_id=${product.product_id}'>
                        <img class='like-img' src='./assets/vote-yes.svg' width='30px' height='30px'>
                    </a>` :  `
                    <a href='/healthy_habitat/like.php?product_id=${product.product_id}'>
                        <img class='like-img' src='./assets/vote-no.svg' width='30px' height='30px'>
                    </a>`): ''
                }

                ${ userRole != 'business' && userRole != 'admin' && userRole != 'user' && userId == 0?                        
                        `<a href='/healthy_habitat/login.php'>
                        <img class='like-img' src='./assets/vote-no.svg' width='30px' height='30px'>
                    </a>`:'' }
               </div>     
            </div>`;
            productContainer.appendChild(card);
        });
    }

    function applyFilters() {
        const priceVal = priceFilter.value;
        const areaVal = areaFilter.value;
        const bizVal = businessFilter.value;
        const searchVal = searchInput.value.trim().toLowerCase();

        let filtered = products.filter(p =>
            (!areaVal || p.area_name === areaVal) &&
            (!bizVal || p.business_name === bizVal) &&
            (!searchVal || p.product_name.toLowerCase().includes(searchVal))
        );

        if (priceVal) {
            filtered = filtered.filter(p => {
                // Here we need to handle the price ranges.
                switch (priceVal) {
                    case 'Affordable':
                        return p.product_price <= 50; // Change as per your price range
                    case 'Moderate':
                        return p.product_price > 50 && p.product_price <= 150; // Adjust as needed
                    case 'Premium':
                        return p.product_price > 150; // Adjust as needed
                    default:
                        return true;
                }
            });
        }
        renderProducts(filtered);

        // Search suggestions
        searchInput.addEventListener("input", () => {
            const query = searchInput.value.trim().toLowerCase();
            if (query.length === 0) {
                suggestionsBox.style.display = "none";
                applyFilters();
                return;
            }

            const suggestions = products
                .filter(p => p.product_name.toLowerCase().includes(query))
                .map(p => p.product_name)
                .filter((name, i, arr) => arr.indexOf(name) === i)
                .slice(0, 5);

            suggestionsBox.innerHTML = suggestions.map(s => `<li class="list-group-item suggestion-item">${s}</li>`).join("");
            suggestionsBox.style.display = suggestions.length > 0 ? "block" : "none";
        });

        suggestionsBox.addEventListener("click", (e) => {
            if (e.target.classList.contains("suggestion-item")) {
                const selected = e.target.textContent;
                searchInput.value = selected;
                suggestionsBox.style.display = "none";
                filterBySearch(selected);
            }
        });

        function filterBySearch(term) {
            const exact = products.filter(p => p.product_name.toLowerCase() === term.toLowerCase());
            renderProducts(exact);
        }
    }

    priceFilter.addEventListener("change", applyFilters);
    areaFilter.addEventListener("change", applyFilters);
    businessFilter.addEventListener("change", applyFilters);
    searchInput.addEventListener("input", applyFilters);

    renderProducts(products);
</script>

<style>
    #suggestions {
        max-height: 200px;
        overflow-y: auto;
        background-color: white;
        width: 100%;
    }

    .suggestion-item:hover {
        background-color: #f0f0f0;
        cursor: pointer;
    }

    .like-img {
        cursor: pointer;
    }
    .badge{
        /* color: black; */
        transform: rotate(45deg);
    }
</style>

<?php
include("./footer.php")
?>