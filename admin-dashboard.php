<?php
session_start();
if (!isset($_SESSION["admin_logged_in"]) && $_SESSION["admin_logged_in"] != true) {
    header("Location: admin-login.php");
    exit();
}

require_once "db/connection.php";

// Fetch Stats for Admin Dashboard
$totalUsers = Database::search("SELECT COUNT(*) as `c` FROM `user`")->fetch_assoc()["c"];
$totalProducts = Database::search("SELECT COUNT(*) as `c` FROM `product`")->fetch_assoc()["c"];
$totalOrders = Database::search("SELECT COUNT(*) as `c` FROM `order`")->fetch_assoc()["c"];
$totalEarnings = Database::search("SELECT SUM(`total_amount`) as s FROM `order`")->fetch_assoc()["s"] ?? 0;

$recentOrders = Database::search(
    "SELECT o.*, u.`fname`, u.`lname`, p.`title`
     FROM `order` o 
     JOIN `user` u ON o.`user_id` = u.`id` 
     JOIN `product` p ON o.`product_id` = p.`id` 
     ORDER BY o.`created_at` DESC LIMIT 5"
);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | SkillShop</title>
    <link rel="icon" type="images/png" href="assets/images/favicon.png" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-50 min-h-screen">

    <div class="flex flex-col md:flex-row min-h-screen">

        <!-- Sidebar -->
        <aside class="w-full md:w-64 bg-slate-900 text-white flex-shrink-0">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-blue-500">SkillShop</h1>
                <p class="text-xs text-slate-400 mt-1 uppercase tracking-widest">Admin Control</p>
            </div>
            <nav class="mt-6 px-4 space-y-2">
                <a href="admin-dashboard.php" class="flex items-center gap-3 px-4 py-3 bg-blue-600 rounded-xl text-sm font-bold shadow-lg shadow-blue-500/20">
                    <span>📊</span> Dashboard
                </a>
                <a href="admin-users.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 rounded-xl text-sm font-medium transition-colors">
                    <span>👥</span> User Management
                </a>
                <a href="admin-products.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 rounded-xl text-sm font-medium transition-colors">
                    <span>🛍️</span> Product Management
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 rounded-xl text-sm font-medium transition-colors">
                    <span>📜</span> Transactions
                </a>
                <a href="#" onclick="openAdminMessagesModal();" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 rounded-xl text-sm font-medium transition-colors">
                    <span>💬</span> Support Messages
                </a>
            </nav>
            <div class="mt-auto p-6 border-t border-slate-800">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-indigo-500 rounded-lg flex items-center justify-center font-bold">A</div>
                    <div>
                        <p class="text-sm font-bold"><?= $_SESSION["admin_fname"] ?></p>
                        <p class="text-[10px] text-slate-400 uppercase">Administrator</p>
                    </div>
                </div>
                <a href="process/adminLogoutProcess.php" class="block w-full text-center py-2 bg-slate-800 hover:bg-red-900/40   hover:text-red-400 rounded-lg text-xs font-bold transition-all border border-slate-700">Logout</a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">

            <!-- Top Nav -->
            <header class="bg-white border-b border-slate-200 px-8 py-4 flex justify-between items-center sticky top-0 z-30">
                <h2 class="text-xl font-extrabold text-slate-900">Dashboard Overview</h2>
                <div class="flex items-center gap-4">
                    <button class="p-2 text-slate-400 hover:text-slate-600">🔔</button>
                    <div class="h-6 w-px bg-slate-200"></div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider"><?= date("D, M j"); ?></span>
                </div>
            </header>

            <div class="p-8">

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-4">
                            <span class="p-3 bg-blue-50 text-blue-600 rounded-xl text-xl">👥</span>
                            <span class="text-[10px] font-bold text-green-500 uppercase">+12%</span>
                        </div>
                        <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Total Users</p>
                        <h3 class="text-3xl font-black text-slate-900"><?= number_format($totalUsers); ?></h3>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-4">
                            <span class="p-3 bg-blue-50 text-blue-600 rounded-xl text-xl">🛍️</span>
                            <span class="text-[10px] font-bold text-green-500 uppercase">Skills</span>
                        </div>
                        <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Active Products</p>
                        <h3 class="text-3xl font-black text-slate-900"><?= number_format($totalProducts); ?></h3>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-4">
                            <span class="p-3 bg-blue-50 text-blue-600 rounded-xl text-xl">💰</span>
                            <span class="text-[10px] font-bold text-green-500 uppercase">Live</span>
                        </div>
                        <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Total Revenue</p>
                        <h3 class="text-3xl font-black text-slate-900">Rs. <?= number_format($totalEarnings, 2); ?></h3>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-4">
                            <span class="p-3 bg-blue-50 text-blue-600 rounded-xl text-xl">📜</span>
                            <span class="text-[10px] font-bold text-green-500 uppercase">Scales</span>
                        </div>
                        <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Total Orders</p>
                        <h3 class="text-3xl font-black text-slate-900"><?= number_format($totalOrders); ?></h3>
                    </div>

                </div>

                <!-- Summarized Report -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">

                    <!-- Category Sales -->
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                        <h3 class="font-bold text-slate-900 mb-6">Sales By Category</h3>
                        <div class="space-y-4">
                            <?php
                                $catSales = Database::search(
                                    "SELECT c.`name`, COUNT(o.`order_id`) AS `count`, SUM(o.`total_amount`) AS `total`
                                    FROM `category` c
                                    LEFT JOIN `product` p ON c.`id`=p.`category_id`
                                    LEFT JOIN `order` o ON p.`id`=o.`product_id`
                                    GROUP BY c.`id` ORDER BY `total` DESC"
                                );
                                while($cat = $catSales->fetch_assoc()):
                                    $percent = $totalEarnings > 0 ? ( $cat["total"] / $totalEarnings) * 100 : 0;
                            ?>
                                <div>
                                    <div class="flex justify-between text-xs mb-1">
                                        <span class="font-bold text-slate-700"><?= $cat["name"] ?></span>
                                        <span class="text-slate-500"><?= number_format($percent, 1) ?>%</span>
                                    </div>
                                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                        <div class="bg-blue-600 h-full transition-all duration-100" style="width:<?= $percent; ?>%;"></div>
                                    </div>
                                </div>        
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <!-- Monthly Trend (Simulation with current data) -->
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                        <h3 class="font-bold text-slate-900 mb-6">Revenue Trend</h3>
                        <div class="flex items-end justify-between h-48 gap-2">
                            <?php 
                                $months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
                                foreach($months as $m):
                                    $h = rand(20,100);
                            ?>
                                <div class="flex-1 flex flex-col items-center gap-2 group">
                                    <div class="w-full bg-slate-50 group-hover:bg-blue-50 rounded-t-lg transition-all relative flex items-end justify-center" style="height: <?= $h; ?>%;">
                                        <div class="w-2/3 bg-blue-100 group-hover:bg-blue-600 h-0 group-hover:h-full transition-all duration-500 rounded-t-md"></div>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase"><?= $m; ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>            
                    </div>

                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center">
                        <h3 class="font-bold text-slate-900">Recent Transactions</h3>
                        <a href="#" class="text-xs font-bold text-blue-600 hover:underline uppercase tracking-widest">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50/50 text-slate-500 text-[10px] font-bold uppercase tracking-widest">
                                    <th class="px-8 py-4">Order ID</th>
                                    <th class="px-8 py-4">Buyer</th>
                                    <th class="px-8 py-4">Product</th>
                                    <th class="px-8 py-4">Amount</th>
                                    <th class="px-8 py-4">Status</th>
                                    <th class="px-8 py-4">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php if($recentOrders && $recentOrders->num_rows > 0): ?>
                                    <?php while($order = $recentOrders->fetch_assoc()): ?>

                                        <tr class="hover:bg-slate-50/50 transitions-colors">

                                            <td class="px-8 py-4 text-sm font-bold text-slate-900"><?= substr($order["order_id"], 0, 8); ?></td>

                                            <td class="px-8 py-4 text-sm text-slate-600 font-medium"><?= $order["fname"] . " " . $order["lname"]; ?></td>

                                            <td class="px-8 py-4 text-sm text-slate-600 font-medium"><?= $order["title"]; ?></td>

                                            <td class="px-8 py-4 text-sm text-slate-600 font-medium"><?= number_format($order["total_amount"], 2); ?></td>

                                            <td class="px-8 py-4">
                                                <span class="px-3 py-1 bg-green-50 text-green-700 text-[10px] font-bold rounded-full uppercase"><?= $order["payment_status"] ?></span>
                                            </td>

                                            <td class="px-8 py-4 text-sm text-slate-600 font-medium"><?= date("M j, Y", strtotime($order["created_at"])); ?></td>

                                        </tr>

                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="px-8 py-12 text-center text-slate-400 italic">No transactions found!</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </main>

    </div>

    <!-- Admin Messages Modal -->
    <div id="adminMessagesModal" class="hidden fixed inset-0 z-50 overflow-hidden flex items-center justify-center">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeAdminMessagesModal();"></div>

        <div class="bg-white w-full max-w-5xl h-[85vh] rounded-3xl shadow-2xl relative z-10 flex overflow-hidden animate-in fade-in zoom-in duration-300 mx-4">

            <!-- Sidebar: User List -->
            <div class="w-1/3 border-r border-slate-100 flex flex-col bg-slate-50">
                <div class="p-6 border-b border-slate-200 flex justify-between items-center bg-white">
                    <h3 class="font-extrabold text-slate-900 text-lg"> Support Inbox</h3>
                    <button onclick="closeAdminMessagesModal()" class="text-slate-400 hover:text-slate-600 block md:hidden">✕</button>
                </div>

                <!-- Search input for user list -->
                <div class="p-4 bg-white border-b border-slate-100">
                    <input type="text" id="adminMsgSearch" placeholder="Search users..." class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-4 foucs:ring-blue-500/10 outline-none" />
                </div>
                <div id="adminMsgUserList" class="flex-1 overflow-y-auto p-4 space-y-2">
                    <div class="text-center text-slate-400 py-10 italic text-sm">Loading users...</div>
                </div>
            </div>

            <!-- Main chat area -->
            <div class="w-2/3  flex flex-col bg-white">
                
                <button onclick="closeAdminMessagesModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 transition-colors hidden md:block">✕</button>

                <!-- Header -->
                <div class="p-6 border-b border-slate-100 flex items-center gap-4 bg-white sticky top-0 z-10 h-[89px]">
                    <div id="chatHeaderAvatar" class="w-10 h-10 bg-slate-100 text-slate-400 rounded-xl flex items-center justify-center font-bold hidden"></div>
                    <div id="chatHeaderName" class="font-bold text-slate-900">Select a conversation</div>
                </div>

                <!-- Chat History -->
                <div id="adminChatContainer" class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-50/50">
                    <div class="h-full flex flex-col items-center justify-center text-slate-400 text-sm">
                        <span class="text-4xl mb-3">💬</span>
                        <p>No chat selected.</p>
                        <p>Select a user from the list to start messaging.</p>
                    </div>
                </div>

                <!-- Input Area -->
                <div class="p-4 sm:p-6 bg-white border-t border-slate-100 hidden" id="adminChatInputArea">
                    <form onsubmit="sendAdminMessage(event);" class="flex gap-2 relative">
                        <input type="hidden" id="activeAdminChatUserId" value="0"/>
                        <input type="text" id="adminChatInput" placeholder="Type your reply to the user..." class="flex-1 px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-blue-500/10 outline-none pr-12" />
                        <button type="submit" class="absolute right-2 top-2 bottom-2 aspect-square flex items-center justify-center bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </form>
                </div>

            </div>

        </div>

    </div>

    <script>

        let adminMessagesInterval = null;

        function openAdminMessagesModal() {
            document.getElementById('adminMessagesModal').classList.remove('hidden');
            loadAdminMessageUsers();
        }

        function closeAdminMessagesModal() {
            document.getElementById('adminMessagesModal').classList.add('hidden');
            if(adminMessagesInterval) clearInterval(adminMessagesInterval);
            adminMessagesInterval = null;
        }

        async function loadAdminMessageUsers() {
            const list = document.getElementById('adminMsgUserList');
            const searchVal = document.getElementById('adminMsgSearch').value.toLowerCase();
            
            try {
                const res = await fetch('process/adminLoadMessages.php?action=getUsers');
                const data = await res.json();
                if(data.success){
                    if(data.users.length == 0){
                        list.innerHTML = '<div class="text-center text-slate-400 py-10 italic text-sm">No conversations yet.</div>';
                        return;
                    }

                    list.innerHTML = data.users.filter(u => {
                        const name = (u.fname + " " + u.lname).toLowerCase();
                        return name.includes(searchVal);
                    }).map(u => `
                        <div onclick="loadAdminChat(${u.id})" class="p-3 bg-white rounded-xl border border-slate-100 hover:border-blue-300 cursor-pointer transition-all flex items-center gap-3 relative group">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl text-white flex items-center justify-center font-bold flex-shrink-0 text-sm shadow-sm">${u.initials}</div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-bold text-slate-900 truncate">${u.fname} ${u.lname}</h4>
                                <p class="text-xs text-slate-500 truncate mt-0.5">${u.last_message}</p>
                            </div>
                            ${u.unseen_count > 0 ? `<div class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 bg-red-500 text-white rounded-full text-[10px] font-bold flex items-center justify-center shadow-md animate-pulse">${u.unseen_count}</div>` : ''}
                        </div>
                    `).join('');
                }
            } catch (e) {
                console.error(e);
            }
        }

        document.getElementById('adminMsgSearch').addEventListener('keyup', loadAdminMessageUsers);

        async function loadAdminChat(userId) {
            document.getElementById('activeAdminChatUserId').value = userId;
            document.getElementById('adminChatInputArea').classList.remove('hidden');
            
            const cont = document.getElementById('adminChatContainer');
            
            try {
                const res = await fetch(`process/adminLoadMessages.php?action=getChat&user_id=${userId}`);
                const data = await res.json();
                
                if(data.success) {

                    document.getElementById('chatHeaderAvatar').classList.remove('hidden');
                    document.getElementById('chatHeaderAvatar').innerHTML = (data.user.fname.charAt(0) + data.user.lname.charAt(0)).toUpperCase();
                    document.getElementById('chatHeaderAvatar').className = 'w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 text-white rounded-xl flex items-center justify-center font-bold text-sm shadow-sm';
                    document.getElementById('chatHeaderName').innerText = `${data.user.fname} ${data.user.lname}`;

                    if(data.chats.length == 0){
                        cont.innerHTML = '<div class="text-center text-slate-400 py-10 italic text-sm">Send your first message.</div>';
                    } else {
                        cont.innerHTML = data.chats.map(c => {
                            const isAdmin = c.sender == 'admin';
                            const date = new Date(c.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                            return `
                                <div class="flex flex-col ${isAdmin ? 'items-end' : 'items-start'}">
                                    <div class="px-5 py-3 rounded-2xl max-w-[80%] shadow-sm ${isAdmin ? 'bg-blue-600 text-white rounded-br-none' : 'bg-white text-slate-800 border border-slate-100 rounded-bl-none'}">
                                        <p class="text-sm leading-relaxed whitespace-pre-wrap">${c.message}</p>
                                    </div>
                                    <span class="text-[10px] text-slate-400 mt-1 font-medium px-1">${date}</span>
                                </div>
                            `;
                        }).join('');
                        cont.scrollTo(0, cont.scrollHeight);
                    }

                    if(adminMessagesInterval) clearInterval(adminMessagesInterval);
                    adminMessagesInterval = setInterval(() => {
                        refreshAdminChat(userId);
                    }, 5000);

                    loadAdminMessageUsers();

                }
            } catch (e) { console.error(e); }
        }

        async function refreshAdminChat(userId) {
            if(document.getElementById('activeAdminChatUserId').value != userId) return;
            const cont = document.getElementById('adminChatContainer');

            const isAtBottom = cont.scrollHeight - cont.scrollTop - cont.clientHeight < 50;

            try {
                const res = await fetch(`process/adminLoadMessages.php?action=getChat&user_id=${userId}`);
                const data = await res.json();
                if(data.success && data.chats.length > 0) {
                     cont.innerHTML = data.chats.map(c => {
                            const isAdmin = c.sender == 'admin';
                            const date = new Date(c.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                            return `
                                <div class="flex flex-col ${isAdmin ? 'items-end' : 'items-start'}">
                                    <div class="px-5 py-3 rounded-2xl max-w-[80%] shadow-sm ${isAdmin ? 'bg-blue-600 text-white rounded-br-none' : 'bg-white text-slate-800 border border-slate-100 rounded-bl-none'}">
                                        <p class="text-sm leading-relaxed whitespace-pre-wrap">${c.message}</p>
                                    </div>
                                    <span class="text-[10px] text-slate-400 mt-1 font-medium px-1">${date}</span>
                                </div>
                            `;
                        }).join('');
                     if(isAtBottom) cont.scrollTo(0, cont.scrollHeight);
                }
            } catch (e) { }
        }

        async function sendAdminMessage(e) {
            e.preventDefault();
            const input = document.getElementById('adminChatInput');
            const uid = document.getElementById('activeAdminChatUserId').value;
            const msg = input.value.trim();

            if(!msg || uid == 0) return;

            const fd = new FormData();
            fd.append('user_id', uid);
            fd.append('message', msg);

            const cont = document.getElementById('adminChatContainer');
            const d = new Date();
            const timeStr = d.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            cont.innerHTML += `
                <div class="flex flex-col items-end opacity-50 transition-opacity duration-300" id="tempMsg">
                    <div class="px-5 py-3 rounded-2xl max-w-[80%] shadow-sm bg-blue-600 text-white rounded-br-none">
                        <p class="text-sm leading-relaxed whitespace-pre-wrap">${msg}</p>
                    </div>
                    <span class="text-[10px] text-slate-400 mt-1 font-medium px-1">${timeStr}</span>
                </div>
            `;
            cont.scrollTo(0, cont.scrollHeight);
            input.value = '';

            try {
                const res = await fetch('process/adminReplyMessage.php', { method: 'POST', body: fd});
                const data = await res.json();
                if(data.success){
                    if(document.getElementById('tempMsg')) document.getElementById('tempMsg').classList.remove('opacity-50');
                    loadAdminMessageUsers(); 
                } else {
                    alert("Error: " + data.message);
                }
            } catch(e) {
                console.error(e);
            }
        }

    </script>

</body>
</html>