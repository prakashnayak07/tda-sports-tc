# Laravel/Filament Migration Plan

## Difficulty Level

- Moderate to High (depending on existing system complexity)
- Estimated timeline: 2-4 weeks for medium-sized application

## Prerequisites Checklist

- [x] Laravel Installation
- [x] Filament Installation
- [x] Default User Setup
- [x] Filament Shield Installation

## Step-by-Step Migration Plan

### 1. Database Structure (Priority: High)

- [ ] Review existing database schema
- [ ] Create Laravel migrations for each table
- [ ] Set up model relationships
- [ ] Create factories for testing

### 2. Authentication & Authorization (Priority: High)

- [ ] Configure Filament authentication
- [ ] Set up Shield roles and permissions
- [ ] Define user policies
- [ ] Migrate existing user data

### 3. Backend Implementation (Priority: High)

- [ ] Create Models
  - User.php
  - [ModelName].php for each entity
- [ ] Create Controllers
  - API controllers if needed
  - Web controllers for non-admin routes
- [ ] Set up Form Requests for validation
- [ ] Implement Services layer (optional)

### 4. Filament Resources (Priority: High)

- [ ] Create Filament resources for each model
- [ ] Implement list/form/table views
- [ ] Set up relationships in resources
- [ ] Configure permissions per resource

### 5. Frontend Migration (Priority: Medium)

- [ ] Set up Blade layouts
- [ ] Create view components
- [ ] Implement frontend routes
- [ ] Migrate assets (CSS/JS)

### 6. API Implementation (if needed) (Priority: Medium)

- [ ] Create API routes
- [ ] Implement API resources
- [ ] Set up API authentication
- [ ] Create API documentation

### 7. Testing (Priority: High)

- [ ] Unit tests for models
- [ ] Feature tests for controllers
- [ ] Integration tests for Filament resources
- [ ] Browser tests for critical paths

## Recommended Folder Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   └── Web/
│   ├── Requests/
│   └── Resources/
├── Filament/
│   ├── Resources/
│   └── Pages/
├── Models/
├── Services/
├── Policies/
└── Providers/

database/
├── migrations/
├── factories/
└── seeders/

resources/
├── views/
│   ├── components/
│   ├── layouts/
│   └── pages/
└── js/

tests/
├── Unit/
├── Feature/
└── Browser/
```

## File Naming Conventions

### Models

```
app/Models/User.php
app/Models/Product.php
```

### Filament Resources

```
app/Filament/Resources/UserResource.php
app/Filament/Resources/UserResource/Pages/ListUsers.php
app/Filament/Resources/UserResource/Pages/CreateUser.php
app/Filament/Resources/UserResource/Pages/EditUser.php
```

### Controllers

```
app/Http/Controllers/Web/ProductController.php
app/Http/Controllers/Api/V1/ProductController.php
```

### Migrations

```
database/migrations/2023_01_01_000000_create_products_table.php
```

## Special Considerations

### Filament Shield

1. Run shield:install and shield:generate
2. Define super_admin role
3. Configure resource permissions
4. Set up role inheritance

### Database

1. Use foreign key constraints
2. Include soft deletes where needed
3. Set up proper indexing

### Security

1. Implement proper authorization
2. Validate all inputs
3. Use secure password hashing
4. Set up CSRF protection

## Implementation Order

1. Database structure
2. Models and relationships
3. Authentication system
4. Core Filament resources
5. Basic frontend structure
6. API endpoints (if needed)
7. Advanced features
8. Testing
9. Documentation

## Quality Assurance Checklist

- [ ] Database integrity
- [ ] Security measures
- [ ] Performance optimization
- [ ] Code documentation
- [ ] User documentation
- [ ] Backup strategy
- [ ] Deployment plan

## Post-Migration Tasks

1. Performance testing
2. Security audit
3. User acceptance testing
4. Documentation review
5. Backup verification
6. Production deployment plan

-
- ## Detailed Implementation Steps
-
- ### Phase 1: Database & Models (Week 1)
-
- #### Day 1-2: Database Analysis & Migration
-
- **Step 1.1: Analyze Existing Database**
- ```bash

  ```

- # Export existing database structure
- mysqldump -u username -p --no-data existing_db > schema.sql
-
- # Document all tables and relationships
- # Create ERD diagram for reference
- ```

  ```

-
- **Step 1.2: Create Laravel Migrations**
- ```bash

  ```

- # Generate migrations for each table
- php artisan make:migration create_users_table
- php artisan make:migration create_products_table
- php artisan make:migration create_orders_table
-
- # Add foreign key constraints
- php artisan make:migration add_foreign_keys_to_orders_table
- ```

  ```

-
- **Step 1.3: Migration Template Example**
- ```php

  ```

- // database/migrations/xxxx_create_products_table.php
- public function up()
- {
-     Schema::create('products', function (Blueprint $table) {
-         $table->id();
-         $table->string('name');
-         $table->text('description')->nullable();
-         $table->decimal('price', 10, 2);
-         $table->integer('stock')->default(0);
-         $table->boolean('is_active')->default(true);
-         $table->timestamps();
-         $table->softDeletes();
-
-         $table->index(['is_active', 'created_at']);
-     });
- }
- ```

  ```

-
- #### Day 3-4: Model Creation
-
- **Step 1.4: Generate Models**
- ```bash

  ```

- php artisan make:model Product -mfs
- php artisan make:model Order -mfs
- php artisan make:model OrderItem -mfs
- ```

  ```

-
- **Step 1.5: Model Template Example**
- ```php

  ```

- // app/Models/Product.php
- <?php
-
- namespace App\Models;
-
- use Illuminate\Database\Eloquent\Factories\HasFactory;
- use Illuminate\Database\Eloquent\Model;
- use Illuminate\Database\Eloquent\SoftDeletes;
-
- class Product extends Model
- {
-     use HasFactory, SoftDeletes;
-
-     protected $fillable = [
-         'name',
-         'description',
-         'price',
-         'stock',
-         'is_active',
-     ];
-
-     protected $casts = [
-         'price' => 'decimal:2',
-         'is_active' => 'boolean',
-     ];
-
-     // Relationships
-     public function orderItems()
-     {
-         return $this->hasMany(OrderItem::class);
-     }
-
-     // Scopes
-     public function scopeActive($query)
-     {
-         return $query->where('is_active', true);
-     }
-
-     // Accessors & Mutators
-     public function getFormattedPriceAttribute()
-     {
-         return '$' . number_format($this->price, 2);
-     }
- }
- ```

  ```

-
- #### Day 5: Factories & Seeders
-
- **Step 1.6: Create Factories**
- ```php

  ```

- // database/factories/ProductFactory.php
- <?php
-
- namespace Database\Factories;
-
- use Illuminate\Database\Eloquent\Factories\Factory;
-
- class ProductFactory extends Factory
- {
-     public function definition()
-     {
-         return [
-             'name' => $this->faker->productName(),
-             'description' => $this->faker->paragraph(),
-             'price' => $this->faker->randomFloat(2, 10, 1000),
-             'stock' => $this->faker->numberBetween(0, 100),
-             'is_active' => $this->faker->boolean(80),
-         ];
-     }
- }
- ```

  ```

-
- **Step 1.7: Create Seeders**
- ```php

  ```

- // database/seeders/ProductSeeder.php
- <?php
-
- namespace Database\Seeders;
-
- use App\Models\Product;
- use Illuminate\Database\Seeder;
-
- class ProductSeeder extends Seeder
- {
-     public function run()
-     {
-         Product::factory(50)->create();
-     }
- }
- ```

  ```

-
- ### Phase 2: Authentication & Authorization (Week 2)
-
- #### Day 6-7: Filament Shield Setup
-
- **Step 2.1: Install and Configure Shield**
- ```bash

  ```

- composer require bezhansalleh/filament-shield
- php artisan vendor:publish --tag=filament-shield-config
- php artisan shield:install --fresh
- ```

  ```

-
- **Step 2.2: Configure User Model**
- ```php

  ```

- // app/Models/User.php
- <?php
-
- namespace App\Models;
-
- use Filament\Models\Contracts\FilamentUser;
- use Filament\Panel;
- use Illuminate\Foundation\Auth\User as Authenticatable;
- use Spatie\Permission\Traits\HasRoles;
-
- class User extends Authenticatable implements FilamentUser
- {
-     use HasRoles;
-
-     protected $fillable = [
-         'name',
-         'email',
-         'password',
-     ];
-
-     public function canAccessPanel(Panel $panel): bool
-     {
-         return $this->hasAnyRole(['super_admin', 'admin', 'moderator']);
-     }
- }
- ```

  ```

-
- **Step 2.3: Create Role Seeder**
- ```php

  ```

- // database/seeders/RoleSeeder.php
- <?php
-
- namespace Database\Seeders;
-
- use Illuminate\Database\Seeder;
- use Spatie\Permission\Models\Role;
- use Spatie\Permission\Models\Permission;
-
- class RoleSeeder extends Seeder
- {
-     public function run()
-     {
-         // Create roles
-         $superAdmin = Role::create(['name' => 'super_admin']);
-         $admin = Role::create(['name' => 'admin']);
-         $moderator = Role::create(['name' => 'moderator']);
-         $user = Role::create(['name' => 'user']);
-
-         // Create permissions
-         $permissions = [
-             'view_any_product',
-             'view_product',
-             'create_product',
-             'update_product',
-             'delete_product',
-             'delete_any_product',
-         ];
-
-         foreach ($permissions as $permission) {
-             Permission::create(['name' => $permission]);
-         }
-
-         // Assign permissions to roles
-         $superAdmin->givePermissionTo(Permission::all());
-         $admin->givePermissionTo(['view_any_product', 'view_product', 'create_product', 'update_product']);
-     }
- }
- ```

  ```

-
- #### Day 8-9: Policies Creation
-
- **Step 2.4: Generate Policies**
- ```bash

  ```

- php artisan make:policy ProductPolicy --model=Product
- php artisan make:policy OrderPolicy --model=Order
- ```

  ```

-
- **Step 2.5: Policy Template**
- ```php

  ```

- // app/Policies/ProductPolicy.php
- <?php
-
- namespace App\Policies;
-
- use App\Models\Product;
- use App\Models\User;
- use Illuminate\Auth\Access\HandlesAuthorization;
-
- class ProductPolicy
- {
-     use HandlesAuthorization;
-
-     public function viewAny(User $user): bool
-     {
-         return $user->can('view_any_product');
-     }
-
-     public function view(User $user, Product $product): bool
-     {
-         return $user->can('view_product');
-     }
-
-     public function create(User $user): bool
-     {
-         return $user->can('create_product');
-     }
-
-     public function update(User $user, Product $product): bool
-     {
-         return $user->can('update_product');
-     }
-
-     public function delete(User $user, Product $product): bool
-     {
-         return $user->can('delete_product');
-     }
-
-     public function deleteAny(User $user): bool
-     {
-         return $user->can('delete_any_product');
-     }
- }
- ```

  ```

-
- ### Phase 3: Filament Resources (Week 2-3)
-
- #### Day 10-12: Core Resources
-
- **Step 3.1: Generate Resources**
- ```bash

  ```

- php artisan make:filament-resource Product --generate
- php artisan make:filament-resource Order --generate
- php artisan make:filament-resource User --generate
- ```

  ```

-
- **Step 3.2: Product Resource Example**
- ```php

  ```

- // app/Filament/Resources/ProductResource.php
- <?php
-
- namespace App\Filament\Resources;
-
- use App\Filament\Resources\ProductResource\Pages;
- use App\Models\Product;
- use Filament\Forms;
- use Filament\Forms\Form;
- use Filament\Resources\Resource;
- use Filament\Tables;
- use Filament\Tables\Table;
-
- class ProductResource extends Resource
- {
-     protected static ?string $model = Product::class;
-     protected static ?string $navigationIcon = 'heroicon-o-cube';
-     protected static ?string $navigationGroup = 'Inventory';
-     protected static ?int $navigationSort = 1;
-
-     public static function form(Form $form): Form
-     {
-         return $form
-             ->schema([
-                 Forms\Components\Section::make('Product Information')
-                     ->schema([
-                         Forms\Components\TextInput::make('name')
-                             ->required()
-                             ->maxLength(255),
-                         Forms\Components\Textarea::make('description')
-                             ->rows(3),
-                         Forms\Components\TextInput::make('price')
-                             ->numeric()
-                             ->prefix('$')
-                             ->required(),
-                         Forms\Components\TextInput::make('stock')
-                             ->numeric()
-                             ->default(0)
-                             ->required(),
-                         Forms\Components\Toggle::make('is_active')
-                             ->default(true),
-                     ])
-                     ->columns(2),
-             ]);
-     }
-
-     public static function table(Table $table): Table
-     {
-         return $table
-             ->columns([
-                 Tables\Columns\TextColumn::make('name')
-                     ->searchable()
-                     ->sortable(),
-                 Tables\Columns\TextColumn::make('price')
-                     ->money('USD')
-                     ->sortable(),
-                 Tables\Columns\TextColumn::make('stock')
-                     ->numeric()
-                     ->sortable(),
-                 Tables\Columns\IconColumn::make('is_active')
-                     ->boolean(),
-                 Tables\Columns\TextColumn::make('created_at')
-                     ->dateTime()
-                     ->sortable()
-                     ->toggleable(isToggledHiddenByDefault: true),
-             ])
-             ->filters([
-                 Tables\Filters\TernaryFilter::make('is_active')
-                     ->label('Active Status'),
-                 Tables\Filters\Filter::make('low_stock')
-                     ->query(fn ($query) => $query->where('stock', '<', 10))
-                     ->label('Low Stock'),
-             ])
-             ->actions([
-                 Tables\Actions\EditAction::make(),
-                 Tables\Actions\DeleteAction::make(),
-             ])
-             ->bulkActions([
-                 Tables\Actions\BulkActionGroup::make([
-                     Tables\Actions\DeleteBulkAction::make(),
-                 ]),
-             ]);
-     }
-
-     public static function getPages(): array
-     {
-         return [
-             'index' => Pages\ListProducts::route('/'),
-             'create' => Pages\CreateProduct::route('/create'),
-             'edit' => Pages\EditProduct::route('/{record}/edit'),
-         ];
-     }
- }
- ```

  ```

-
- #### Day 13-14: Advanced Resource Features
-
- **Step 3.3: Custom Pages**
- ```php

  ```

- // app/Filament/Resources/ProductResource/Pages/ListProducts.php
- <?php
-
- namespace App\Filament\Resources\ProductResource\Pages;
-
- use App\Filament\Resources\ProductResource;
- use Filament\Actions;
- use Filament\Resources\Pages\ListRecords;
- use Filament\Resources\Components\Tab;
-
- class ListProducts extends ListRecords
- {
-     protected static string $resource = ProductResource::class;
-
-     protected function getHeaderActions(): array
-     {
-         return [
-             Actions\CreateAction::make(),
-         ];
-     }
-
-     public function getTabs(): array
-     {
-         return [
-             'all' => Tab::make('All Products'),
-             'active' => Tab::make('Active')
-                 ->modifyQueryUsing(fn ($query) => $query->where('is_active', true)),
-             'inactive' => Tab::make('Inactive')
-                 ->modifyQueryUsing(fn ($query) => $query->where('is_active', false)),
-             'low_stock' => Tab::make('Low Stock')
-                 ->modifyQueryUsing(fn ($query) => $query->where('stock', '<', 10)),
-         ];
-     }
- }
- ```

  ```

-
- **Step 3.4: Widgets Creation**
- ```bash

  ```

- php artisan make:filament-widget ProductStatsWidget --stats-overview
- ```

  ```

-
- ```php

  ```

- // app/Filament/Widgets/ProductStatsWidget.php
- <?php
-
- namespace App\Filament\Widgets;
-
- use App\Models\Product;
- use Filament\Widgets\StatsOverviewWidget as BaseWidget;
- use Filament\Widgets\StatsOverviewWidget\Stat;
-
- class ProductStatsWidget extends BaseWidget
- {
-     protected function getStats(): array
-     {
-         return [
-             Stat::make('Total Products', Product::count())
-                 ->description('All products in inventory')
-                 ->descriptionIcon('heroicon-m-cube')
-                 ->color('success'),
-             Stat::make('Active Products', Product::where('is_active', true)->count())
-                 ->description('Currently active products')
-                 ->descriptionIcon('heroicon-m-check-circle')
-                 ->color('primary'),
-             Stat::make('Low Stock Items', Product::where('stock', '<', 10)->count())
-              +                 ->description('Products with low stock')
-                 ->descriptionIcon('heroicon-m-exclamation-triangle')
-                 ->color('warning'),
-         ];
-     }
- }
- ```

  ```

-
- ### Phase 4: Frontend Implementation (Week 3)
-
- #### Day 15-16: Frontend Structure
-
- **Step 4.1: Create Frontend Controllers**
- ```bash

  ```

- php artisan make:controller Web/HomeController
- php artisan make:controller Web/ProductController
- php artisan make:controller Web/OrderController
- ```

  ```

-
- **Step 4.2: Frontend Controller Example**
- ```php

  ```

- // app/Http/Controllers/Web/ProductController.php
- <?php
-
- namespace App\Http\Controllers\Web;
-
- use App\Http\Controllers\Controller;
- use App\Models\Product;
- use Illuminate\Http\Request;
- use Illuminate\View\View;
-
- class ProductController extends Controller
- {
-     public function index(): View
-     {
-         $products = Product::active()
-             ->paginate(12);
-
-         return view('frontend.products.index', compact('products'));
-     }
-
-     public function show(Product $product): View
-     {
-         abort_if(!$product->is_active, 404);
-
-         return view('frontend.products.show', compact('product'));
-     }
- }
- ```

  ```

-
- **Step 4.3: Create Blade Layouts**
- ```php

  ```

- <!-- resources/views/layouts/app.blade.php -->
- <!DOCTYPE html>
- <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
- <head>
-     <meta charset="utf-8">
-     <meta name="viewport" content="width=device-width, initial-scale=1">
-     <meta name="csrf-token" content="{{ csrf_token() }}">
-
-     <title>{{ config('app.name', 'Laravel') }}</title>
-
-     <!-- Fonts -->
-     <link rel="preconnect" href="https://fonts.bunny.net">
-     <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
-
-     <!-- Scripts -->
-     @vite(['resources/css/app.css', 'resources/js/app.js'])
- </head>
- <body class="font-sans antialiased">
-     <div class="min-h-screen bg-gray-100">
-         @include('layouts.navigation')
-
-         <!-- Page Heading -->
-         @if (isset($header))
-             <header class="bg-white shadow">
-                 <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
-                     {{ $header }}
-                 </div>
-             </header>
-         @endif
-
-         <!-- Page Content -->
-         <main>
-             {{ $slot }}
-         </main>
-     </div>
- </body>
- </html>
- ```

  ```

-
- **Step 4.4: Create Frontend Views**
- ```php

  ```

- <!-- resources/views/frontend/products/index.blade.php -->
- <x-app-layout>
-     <x-slot name="header">
-         <h2 class="font-semibold text-xl text-gray-800 leading-tight">
-             {{ __('Products') }}
-         </h2>
-     </x-slot>
-
-     <div class="py-12">
-         <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
-             <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
-                 <div class="p-6 text-gray-900">
-                     <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
-                         @foreach($products as $product)
-                             <div class="bg-white rounded-lg shadow-md overflow-hidden">
-                                 <div class="p-4">
-                                     <h3 class="text-lg font-semibold mb-2">{{ $product->name }}</h3>
-                                     <p class="text-gray-600 text-sm mb-4">{{ Str::limit($product->description, 100) }}</p>
-                                     <div class="flex justify-between items-center">
-                                         <span class="text-xl font-bold text-green-600">{{ $product->formatted_price }}</span>
-                                         <a href="{{ route('products.show', $product) }}"
-                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
-                                             View Details
-                                         </a>
-                                     </div>
-                                 </div>
-                             </div>
-                         @endforeach
-                     </div>
-
-                     <div class="mt-8">
-                         {{ $products->links() }}
-                     </div>
-                 </div>
-             </div>
-         </div>
-     </div>
- </x-app-layout>
- ```

  ```

-
- #### Day 17-18: Form Requests & Validation
-
- **Step 4.5: Create Form Requests**
- ```bash

  ```

- php artisan make:request StoreProductRequest
- php artisan make:request UpdateProductRequest
- ```

  ```

-
- **Step 4.6: Form Request Example**
- ```php

  ```

- // app/Http/Requests/StoreProductRequest.php
- <?php
-
- namespace App\Http\Requests;
-
- use Illuminate\Foundation\Http\FormRequest;
-
- class StoreProductRequest extends FormRequest
- {
-     public function authorize(): bool
-     {
-         return $this->user()->can('create', Product::class);
-     }
-
-     public function rules(): array
-     {
-         return [
-             'name' => ['required', 'string', 'max:255'],
-             'description' => ['nullable', 'string'],
-             'price' => ['required', 'numeric', 'min:0'],
-             'stock' => ['required', 'integer', 'min:0'],
-             'is_active' => ['boolean'],
-         ];
-     }
-
-     public function messages(): array
-     {
-         return [
-             'name.required' => 'Product name is required.',
-             'price.required' => 'Product price is required.',
-             'price.numeric' => 'Price must be a valid number.',
-         ];
-     }
- }
- ```

  ```

-
- ### Phase 5: API Implementation (Week 3-4)
-
- #### Day 19-20: API Setup
-
- **Step 5.1: Create API Controllers**
- ```bash

  ```

- php artisan make:controller Api/V1/ProductController --api
- php artisan make:controller Api/V1/OrderController --api
- ```

  ```

-
- **Step 5.2: API Controller Example**
- ```php

  ```

- // app/Http/Controllers/Api/V1/ProductController.php
- <?php
-
- namespace App\Http\Controllers\Api\V1;
-
- use App\Http\Controllers\Controller;
- use App\Http\Requests\StoreProductRequest;
- use App\Http\Requests\UpdateProductRequest;
- use App\Http\Resources\ProductResource;
- use App\Models\Product;
- use Illuminate\Http\Request;
- use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
-
- class ProductController extends Controller
- {
-     public function index(Request $request): AnonymousResourceCollection
-     {
-         $products = Product::query()
-             ->when($request->search, function ($query, $search) {
-                 $query->where('name', 'like', "%{$search}%");
-             })
-             ->when($request->active, function ($query) {
-                 $query->where('is_active', true);
-             })
-             ->paginate($request->per_page ?? 15);
-
-         return ProductResource::collection($products);
-     }
-
-     public function store(StoreProductRequest $request): ProductResource
-     {
-         $product = Product::create($request->validated());
-
-         return new ProductResource($product);
-     }
-
-     public function show(Product $product): ProductResource
-     {
-         return new ProductResource($product);
-     }
-
-     public function update(UpdateProductRequest $request, Product $product): ProductResource
-     {
-         $product->update($request->validated());
-
-         return new ProductResource($product);
-     }
-
-     public function destroy(Product $product): \Illuminate\Http\JsonResponse
-     {
-         $product->delete();
-
-         return response()->json(['message' => 'Product deleted successfully']);
-     }
- }
- ```

  ```

-
- **Step 5.3: Create API Resources**
- ```bash

  ```

- php artisan make:resource ProductResource
- php artisan make:resource ProductCollection
- ```

  ```

-
- **Step 5.4: API Resource Example**
- ```php

  ```

- // app/Http/Resources/ProductResource.php
- <?php
-
- namespace App\Http\Resources;
-
- use Illuminate\Http\Request;
- use Illuminate\Http\Resources\Json\JsonResource;
-
- class ProductResource extends JsonResource
- {
-     public function toArray(Request $request): array
-     {
-         return [
-             'id' => $this->id,
-             'name' => $this->name,
-             'description' => $this->description,
-             'price' => $this->price,
-             'formatted_price' => $this->formatted_price,
-             'stock' => $this->stock,
-             'is_active' => $this->is_active,
-             'created_at' => $this->created_at,
-             'updated_at' => $this->updated_at,
-         ];
-     }
- }
- ```

  ```

-
- #### Day 21: Routes Configuration
-
- **Step 5.5: Web Routes**
- ```php

  ```

- // routes/web.php
- <?php
-
- use App\Http\Controllers\Web\HomeController;
- use App\Http\Controllers\Web\ProductController;
- use Illuminate\Support\Facades\Route;
-
- Route::get('/', [HomeController::class, 'index'])->name('home');
-
- Route::prefix('products')->name('products.')->group(function () {
-     Route::get('/', [ProductController::class, 'index'])->name('index');
-     Route::get('/{product}', [ProductController::class, 'show'])->name('show');
- });
-
- Route::middleware(['auth'])->group(function () {
-     Route::get('/dashboard', function () {
-         return view('dashboard');
-     })->name('dashboard');
- });
-
- require **DIR**.'/auth.php';
- ```

  ```

-
- **Step 5.6: API Routes**
- ```php

  ```

- // routes/api.php
- <?php
-
- use App\Http\Controllers\Api\V1\ProductController;
- use Illuminate\Http\Request;
- use Illuminate\Support\Facades\Route;
-
- Route::get('/user', function (Request $request) {
-     return $request->user();
- })->middleware('auth:sanctum');
-
- Route::prefix('v1')->group(function () {
-     Route::apiResource('products', ProductController::class);
- });
- ```

  ```

-
- ### Phase 6: Testing (Week 4)
-
- #### Day 22-23: Unit & Feature Tests
-
- **Step 6.1: Create Test Files**
- ```bash

  ```

- php artisan make:test ProductTest
- php artisan make:test ProductControllerTest
- php artisan make:test --unit ProductModelTest
- ```

  ```

-
- **Step 6.2: Model Test Example**
- ```php

  ```

- // tests/Unit/ProductModelTest.php
- <?php
-
- namespace Tests\Unit;
-
- use App\Models\Product;
- use PHPUnit\Framework\TestCase;
- use Illuminate\Foundation\Testing\RefreshDatabase;
-
- class ProductModelTest extends TestCase
- {
-     use RefreshDatabase;
-
-     public function test_product_can_be_created()
-     {
-         $product = Product::factory()->create([
-             'name' => 'Test Product',
-             'price' => 99.99,
-         ]);
-
-         $this->assertEquals('Test Product', $product->name);
-         $this->assertEquals(99.99, $product->price);
-     }
-
-     public function test_formatted_price_accessor()
-     {
-         $product = Product::factory()->create(['price' => 99.99]);
-
-         $this->assertEquals('$99.99', $product->formatted_price);
-     }
-
-     public function test_active_scope()
-     {
-         Product::factory()->create(['is_active' => true]);
-         Product::factory()->create(['is_active' => false]);
-
-         $activeProducts = Product::active()->get();
-
-         $this->assertCount(1, $activeProducts);
-     }
- }
- ```

  ```

-
- **Step 6.3: Feature Test Example**
- ```php

  ```

- // tests/Feature/ProductControllerTest.php
- <?php
-
- namespace Tests\Feature;
-
- use App\Models\Product;
- use App\Models\User;
- use Illuminate\Foundation\Testing\RefreshDatabase;
- use Tests\TestCase;
-
- class ProductControllerTest extends TestCase
- {
-     use RefreshDatabase;
-
-     public function test_products_index_page_loads()
-     {
-         Product::factory(5)->create();
-
-         $response = $this->get('/products');
-
-         $response->assertStatus(200);
-         $response->assertViewIs('frontend.products.index');
-     }
-
-     public function test_product_show_page_loads()
-     {
-         $product = Product::factory()->create(['is_active' => true]);
-
-         $response = $this->get("/products/{$product->id}");
-
-         $response->assertStatus(200);
-         $response->assertViewIs('frontend.products.show');
-         $response->assertSee($product->name);
-     }
-
-     public function test_inactive_product_returns_404()
-     {
-         $product = Product::factory()->create(['is_active' => false]);
-
-         $response = $this->get("/products/{$product->id}");
-
-         $response->assertStatus(404);
-     }
- }
- ```

  ```

-
- #### Day 24-25: Integration & Browser Tests
-
- **Step 6.4: Browser Test Example**
- ```php

  ```

- // tests/Browser/ProductManagementTest.php
- <?php
-
- namespace Tests\Browser;
-
- use App\Models\Product;
- use App\Models\User;
- use Illuminate\Foundation\Testing\DatabaseMigrations;
- use Laravel\Dusk\Browser;
- use Tests\DuskTestCase;
-
- class ProductManagementTest extends DuskTestCase
- {
-     use DatabaseMigrations;
-
-     public function test_admin_can_create_product()
-     {
-         $admin = User::factory()->create();
-         $admin->assignRole('super_admin');
-
-         $this->browse(function (Browser $browser) use ($admin) {
-             $browser->loginAs($admin)
-                     ->visit('/admin/products')
-                     ->clickLink('New')
-                     ->type('name', 'Test Product')
-                     ->type('description', 'Test Description')
-                     ->type('price', '99.99')
-                     ->type('stock', '10')
-                     ->press('Create')
-                     ->assertSee('Product created successfully');
-         });
-     }
-
-     public function test_user_can_view_products()
-     {
-         Product::factory(3)->create(['is_active' => true]);
-
-         $this->browse(function (Browser $browser) {
-             $browser->visit('/products')
-                     ->assertSee('Products')
-                     ->assertElementCount('.product-card', 3);
-         });
-     }
- }
- ```

  ```

-
- ### Phase 7: Advanced Features & Optimization (Week 4-5)
-
- #### Day 26-27: Services & Jobs
-
- **Step 7.1: Create Service Classes**
- ```bash

  ```

- mkdir app/Services
- ```

  ```

-
- **Step 7.2: Service Example**
- ```php

  ```

- // app/Services/ProductService.php
- <?php
-
- namespace App\Services;
-
- use App\Models\Product;
- use Illuminate\Database\Eloquent\Collection;
- use Illuminate\Pagination\LengthAwarePaginator;
-
- class ProductService
- {
-     public function getActiveProducts(int $perPage = 15): LengthAwarePaginator
-     {
-         return Product::active()
-             ->orderBy('created_at', 'desc')
-             ->paginate($perPage);
-     }
-
-     public function searchProducts(string $query, int $perPage = 15): LengthAwarePaginator
-     {
-         return Product::active()
-             ->where('name', 'like', "%{$query}%")
-             ->orWhere('description', 'like', "%{$query}%")
-             ->paginate($perPage);
-     }
-
-     public function getLowStockProducts(int $threshold = 10): Collection
-     {
-         return Product::active()
-             ->where('stock', '<', $threshold)
-             ->get();
-     }
-
-     public function updateStock(Product $product, int $quantity): bool
-     {
-         if ($product->stock + $quantity < 0) {
-             return false;
-         }
-
-         $product->increment('stock', $quantity);
-         return true;
-     }
- }
- ```

  ```

-
- **Step 7.3: Create Jobs**
- ```bash

  ```

- php artisan make:job ProcessLowStockAlert
- php artisan make:job SendProductNotification
- ```

  ```

-
- **Step 7.4: Job Example**
- ```php

  ```

- // app/Jobs/ProcessLowStockAlert.php
- <?php
-
- namespace App\Jobs;
-
- use App\Models\Product;
- use App\Models\User;
- use App\Notifications\LowStockAlert;
- use Illuminate\Bus\Queueable;
- use Illuminate\Contracts\Queue\ShouldQueue;
- use Illuminate\Foundation\Bus\Dispatchable;
- use Illuminate\Queue\InteractsWithQueue;
- use Illuminate\Queue\SerializesModels;
-
- class ProcessLowStockAlert implements ShouldQueue
- {
-     use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
-
-     public function __construct(
-         public Product $product
-     ) {}
-
-     public function handle(): void
-     {
-         $admins = User::role('admin')->get();
-
-         foreach ($admins as $admin) {
-             $admin->notify(new LowStockAlert($this->product));
-         }
-     }
- }
- ```

  ```

-
- #### Day 28: Notifications & Mail
-
- **Step 7.5: Create Notifications**
- ```bash

  ```

- php artisan make:notification LowStockAlert
- php artisan make:notification ProductCreated
- ```

  ```

-
- **Step 7.6: Notification Example**
- ```php

  ```

- // app/Notifications/LowStockAlert.php
- <?php
-
- namespace App\Notifications;
-
- use App\Models\Product;
- use Illuminate\Bus\Queueable;
- use Illuminate\Contracts\Queue\ShouldQueue;
- use Illuminate\Notifications\Messages\MailMessage;
- use Illuminate\Notifications\Notification;
-
- class LowStockAlert extends Notification implements ShouldQueue
- {
-     use Queueable;
-
-     public function __construct(
-         public Product $product
-     ) {}
-
-     public function via(object $notifiable): array
-     {
-         return ['mail', 'database'];
-     }
-
-     public function toMail(object $notifiable): MailMessage
-     {
-         return (new MailMessage)
-                     ->subject('Low Stock Alert')
-                     ->line("Product '{$this->product->name}' is running low on stock.")
-                     ->line("Current stock: {$this->product->stock}")
-                     ->action('View Product', url("/admin/products/{$this->product->id}/edit"))
-                     ->line('Please restock as soon as possible.');
-     }
-
-     public function toArray(object $notifiable): array
-     {
-         return [
-             'product_id' => $this->product->id,
-             'product_name' => $this->product->name,
-             'current_stock' => $this->product->stock,
-             'message' => "Product '{$this->product->name}' is running low on stock.",
-         ];
-     }
- }
- ```

  ```

-
- **Step 7.7: Create Mail Classes**
- ```bash

  ```

- php artisan make:mail ProductCreatedMail
- ```

  ```

-
- ```php

  ```

- // app/Mail/ProductCreatedMail.php
- <?php
-
- namespace App\Mail;
-
- use App\Models\Product;
- use Illuminate\Bus\Queueable;
- use Illuminate\Contracts\Queue\ShouldQueue;
- use Illuminate\Mail\Mailable;
- use Illuminate\Mail\Mailables\Content;
- use Illuminate\Mail\Mailables\Envelope;
- use Illuminate\Queue\SerializesModels;
-
- class ProductCreatedMail extends Mailable implements ShouldQueue
- {
-     use Queueable, SerializesModels;
-
-     public function __construct(
-         public Product $product
-     ) {}
-
-     public function envelope(): Envelope
-     {
-         return new Envelope(
-             subject: 'New Product Created: ' . $this->product->name,
-         );
-     }
-
-     public function content(): Content
-     {
-         return new Content(
-             view: 'emails.product-created',
-             with: [
-                 'product' => $this->product,
-             ],
-         );
-     }
- }
- ```

  ```

-
- #### Day 29-30: Performance & Security
-
- **Step 7.8: Add Caching**
- ```php

  ```

- // app/Services/ProductService.php (Updated)
- <?php
-
- namespace App\Services;
-
- use App\Models\Product;
- use Illuminate\Support\Facades\Cache;
- use Illuminate\Database\Eloquent\Collection;
-
- class ProductService
- {
-     public function getFeaturedProducts(): Collection
-     {
-         return Cache::remember('featured_products', 3600, function () {
-             return Product::active()
-                 ->where('featured', true)
-                 ->limit(8)
-                 ->get();
-         });
-     }
-
-     public function getProductStats(): array
-     {
-         return Cache::remember('product_stats', 1800, function () {
-             return [
-                 'total' => Product::count(),
-                 'active' => Product::active()->count(),
-                 'low_stock' => Product::where('stock', '<', 10)->count(),
-                 'out_of_stock' => Product::where('stock', 0)->count(),
-             ];
-         });
-     }
-
-     public function clearProductCache(): void
-     {
-         Cache::forget('featured_products');
-         Cache::forget('product_stats');
-     }
- }
- ```

  ```

-
- **Step 7.9: Add Model Observers**
- ```bash

  ```

- php artisan make:observer ProductObserver --model=Product
- ```

  ```

-
- ```php

  ```

- // app/Observers/ProductObserver.php
- <?php
-
- namespace App\Observers;
-
- use App\Jobs\ProcessLowStockAlert;
- use App\Models\Product;
- use App\Services\ProductService;
-
- class ProductObserver
- {
-     public function __construct(
-         private ProductService $productService
-     ) {}
-
-     public function created(Product $product): void
-     {
-         $this->productService->clearProductCache();
-
-         // Log product creation
-         activity()
-             ->performedOn($product)
-             ->log('Product created');
-     }
-
-     public function updated(Product $product): void
-     {
-         $this->productService->clearProductCache();
-
-         // Check for low stock
-         if ($product->stock < 10 && $product->is_active) {
-             ProcessLowStockAlert::dispatch($product);
-         }
-
-         // Log significant changes
-         if ($product->wasChanged(['price', 'stock', 'is_active'])) {
-             activity()
-                 ->performedOn($product)
-                 ->withProperties([
-                     'old' => $product->getOriginal(),
-                     'new' => $product->getAttributes(),
-                 ])
-                 ->log('Product updated');
-         }
-     }
-
-     public function deleted(Product $product): void
-     {
-         $this->productService->clearProductCache();
-
-         activity()
-             ->performedOn($product)
-             ->log('Product deleted');
-     }
- }
- ```

  ```

-
- **Step 7.10: Register Observer**
- ```php

  ```

- // app/Providers/AppServiceProvider.php
- <?php
-
- namespace App\Providers;
-
- use App\Models\Product;
- use App\Observers\ProductObserver;
- use Illuminate\Support\ServiceProvider;
-
- class AppServiceProvider extends ServiceProvider
- {
-     public function boot(): void
-     {
-         Product::observe(ProductObserver::class);
-     }
- }
- ```

  ```

-
- ## Final Checklist & Deployment
-
- ### Pre-Deployment Checklist
-
- #### Security
- - [ ] All routes properly protected with middleware
- - [ ] CSRF protection enabled
- - [ ] SQL injection prevention (using Eloquent/Query Builder)
- - [ ] XSS protection in Blade templates
- - [ ] File upload validation and sanitization
- - [ ] Rate limiting configured
- - [ ] Environment variables secured
- - [ ] Debug mode disabled in production
-
- #### Performance
- - [ ] Database queries optimized (N+1 problem solved)
- - [ ] Proper indexing on database tables
- - [ ] Caching implemented where appropriate
- - [ ] Image optimization
- - [ ] Asset compilation and minification
- - [ ] Queue workers configured
- - [ ] Session and cache drivers optimized
-
- #### Testing
- - [ ] Unit tests passing
- - [ ] Feature tests passing
- - [ ] Browser tests passing
- - [ ] API tests passing
- - [ ] Performance tests completed
- - [ ] Security tests completed
-
- #### Documentation
- - [ ] API documentation updated
- - [ ] User manual created
- - [ ] Admin guide created
- - [ ] Deployment guide created
- - [ ] Code documentation complete
-
- ### Deployment Steps
-
- **Step 8.1: Production Environment Setup**
- ```bash

  ```

- # Clone repository
- git clone <repository-url> production-app
- cd production-app
-
- # Install dependencies
- composer install --optimize-autoloader --no-dev
- npm install && npm run build
-
- # Environment configuration
- cp .env.example .env
- php artisan key:generate
-
- # Database setup
- php artisan migrate --force
- php artisan db:seed --force
-
- # Cache optimization
- php artisan config:cache
- php artisan route:cache
- php artisan view:cache
- php artisan event:cache
-
- # Storage link
- php artisan storage:link
-
- # Queue workers
- php artisan queue:restart
- ```

  ```

-
- **Step 8.2: Server Configuration**
- ```nginx

  ```

- # nginx configuration example
- server {
-     listen 80;
-     server_name your-domain.com;
-     root /var/www/html/public;
-
-     add_header X-Frame-Options "SAMEORIGIN";
-     add_header X-Content-Type-Options "nosniff";
-
-     index index.php;
-
-     charset utf-8;
-
-     location / {
-         try_files $uri $uri/ /index.php?$query_string;
-     }
-
-     location = /favicon.ico { access_log off; log_not_found off; }
-     location = /robots.txt  { access_log off; log_not_found off; }
-
-     error_page 404 /index.php;
-
-     location ~ \.php$ {
-         fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
-         fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
-         include fastcgi_params;
-     }
-
-     location ~ /\.(?!well-known).* {
-         deny all;
-     }
- }
- ```

  ```

-
- ### Post-Migration Monitoring
-
- #### Performance Monitoring
- - [ ] Set up application monitoring (Laravel Telescope, Bugsnag, etc.)
- - [ ] Database performance monitoring
- - [ ] Server resource monitoring
- - [ ] Error tracking and logging
- - [ ] User activity monitoring
-
- #### Backup Strategy
- - [ ] Database backup automation
- - [ ] File storage backup
- - [ ] Configuration backup
- - [ ] Recovery testing procedures
- - [ ] Backup restoration documentation
-
- #### Maintenance Schedule
- - [ ] Regular security updates
- - [ ] Database optimization
- - [ ] Log file rotation
- - [ ] Cache clearing schedule
- - [ ] Performance review schedule
-
- ## Troubleshooting Guide
-
- ### Common Migration Issues
-
- #### Database Issues
- ```bash

  ```

- # Foreign key constraint errors
- php artisan migrate:fresh --seed
-
- # Migration rollback
- php artisan migrate:rollback --step=1
-
- # Check migration status
- php artisan migrate:status
- ```

  ```

-
- #### Permission Issues
- ```bash

  ```

- # Fix storage permissions
- sudo chown -R www-data:www-data storage/
- sudo chmod -R 775 storage/
-
- # Fix bootstrap cache permissions
- sudo chown -R www-data:www-data bootstrap/cache/
- sudo chmod -R 775 bootstrap/cache/
- ```

  ```

-
- #### Filament Issues
- ```bash

  ```

- # Clear Filament cache
- php artisan filament:clear-cached-components
-
- # Regenerate Shield permissions
- php artisan shield:generate --all
-
- # Check Filament configuration
- php artisan filament:check
- ```

  ```

-
- #### Performance Issues
- ```bash

  ```

- # Clear all caches
- php artisan optimize:clear
-
- # Rebuild caches
- php artisan optimize
-
- # Check queue status
- php artisan queue:monitor
-
- # Restart queue workers
- php artisan queue:restart
- ```

  ```

-
- ### Error Resolution
-
- #### 500 Internal Server Error
- 1. Check Laravel logs: `storage/logs/laravel.log`
- 2. Check web server error logs
- 3. Verify file permissions
- 4. Check environment configuration
- 5. Verify database connection
-
- #### 403 Forbidden Error
- 1. Check Shield permissions
- 2. Verify user roles
- 3. Check policy definitions
- 4. Review middleware configuration
-
- #### Memory Limit Issues
- ```php

  ```

- // In php.ini or .htaccess
- memory_limit = 512M
- max_execution_time = 300
- ```

  ```

-
- ## Advanced Customizations
-
- ### Custom Filament Components
-
- **Step 9.1: Custom Form Component**
- ```bash

  ```

- php artisan make:filament-form-component RichEditor
- ```

  ```

-
- ```php

  ```

- // app/Filament/Forms/Components/RichEditor.php
- <?php
-
- namespace App\Filament\Forms\Components;
-
- use Filament\Forms\Components\Field;
-
- class RichEditor extends Field
- {
-     protected string $view = 'filament.forms.components.rich-editor';
-
-     public function toolbar(array $toolbar): static
-     {
-         $this->toolbar = $toolbar;
-         return $this;
-     }
-
-     public function getToolbar(): array
-     {
-         return $this->toolbar ?? [
-             'bold', 'italic', 'underline', 'link', 'bulletList', 'orderedList'
-         ];
-     }
- }
- ```

  ```

-
- **Step 9.2: Custom Table Column**
- ```bash

  ```

- php artisan make:filament-table-column StatusBadge
- ```

  ```

-
- ```php

  ```

- // app/Filament/Tables/Columns/StatusBadge.php
- <?php
-
- namespace App\Filament\Tables\Columns;
-
- use Filament\Tables\Columns\Column;
-
- class StatusBadge extends Column
- {
-     protected string $view = 'filament.tables.columns.status-badge';
-
-     public function colors(array $colors): static
-     {
-         $this->colors = $colors;
-         return $this;
-     }
-
-     public function getColors(): array
-     {
-         return $this->colors ?? [];
-     }
- }
- ```

  ```

-
- ### Custom Filament Pages
-
- **Step 9.3: Analytics Dashboard**
- ```bash

  ```

- php artisan make:filament-page Analytics
- ```

  ```

-
- ```php

  ```

- // app/Filament/Pages/Analytics.php
- <?php
-
- namespace App\Filament\Pages;
-
- use Filament\Pages\Page;
- use Filament\Widgets\StatsOverviewWidget\Stat;
-
- class Analytics extends Page
- {
-     protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
-     protected static string $view = 'filament.pages.analytics';
-     protected static ?string $navigationGroup = 'Reports';
-     protected static ?int $navigationSort = 1;
-
-     public function getHeaderWidgets(): array
-     {
-         return [
-             AnalyticsStatsWidget::class,
-             SalesChartWidget::class,
-         ];
-     }
-
-     protected function getStats(): array
-     {
-         return [
-             Stat::make('Total Revenue', '$' . number_format($this->getTotalRevenue(), 2))
-                 ->description('This month')
-                 ->descriptionIcon('heroicon-m-arrow-trending-up')
-                 ->color('success'),
-             Stat::make('New Customers', $this->getNewCustomers())
-                 ->description('This month')
-                 ->descriptionIcon('heroicon-m-user-plus')
-                 ->color('primary'),
-         ];
-     }
-
-     private function getTotalRevenue(): float
-     {
-         // Implementation for revenue calculation
-         return 0.0;
-     }
-
-     private function getNewCustomers(): int
-     {
-         // Implementation for new customers count
-         return 0;
-     }
- }
- ```

  ```

-
- ### Multi-tenancy Setup (Optional)
-
- **Step 9.4: Tenant Model**
- ```bash

  ```

- php artisan make:model Tenant -m
- ```

  ```

-
- ```php

  ```

- // app/Models/Tenant.php
- <?php
-
- namespace App\Models;
-
- use Illuminate\Database\Eloquent\Model;
- use Illuminate\Database\Eloquent\Relations\HasMany;
-
- class Tenant extends Model
- {
-     protected $fillable = [
-         'name',
-         'slug',
-         'domain',
-         'database',
-         'is_active',
-     ];
-
-     protected $casts = [
-         'is_active' => 'boolean',
-     ];
-
-     public function users(): HasMany
-     {
-         return $this->hasMany(User::class);
-     }
-
-     public function products(): HasMany
-     {
-         return $this->hasMany(Product::class);
-     }
- }
- ```

  ```

-
- ## Data Migration Scripts
-
- ### Legacy Data Import
-
- **Step 10.1: Create Import Command**
- ```bash

  ```

- php artisan make:command ImportLegacyData
- ```

  ```

-
- ```php

  ```

- // app/Console/Commands/ImportLegacyData.php
- <?php
-
- namespace App\Console\Commands;
-
- use App\Models\Product;
- use App\Models\User;
- use Illuminate\Console\Command;
- use Illuminate\Support\Facades\DB;
- use Illuminate\Support\Facades\Hash;
-
- class ImportLegacyData extends Command
- {
-     protected $signature = 'import:legacy-data {--table=all}';
-     protected $description = 'Import data from legacy system';
-
-     public function handle()
-     {
-         $table = $this->option('table');
-
-         if ($table === 'all' || $table === 'users') {
-             $this->importUsers();
-         }
-
-         if ($table === 'all' || $table === 'products') {
-             $this->importProducts();
-         }
-
-         $this->info('Data import completed successfully!');
-     }
-
-     private function importUsers()
-     {
-         $this->info('Importing users...');
-
-         $legacyUsers = DB::connection('legacy')
-             ->table('users')
-             ->get();
-
-         $bar = $this->output->createProgressBar($legacyUsers->count());
-
-         foreach ($legacyUsers as $legacyUser) {
-             User::updateOrCreate(
-                 ['email' => $legacyUser->email],
-                 [
-                     'name' => $legacyUser->name,
-                     'password' => Hash::make('temporary_password'),
-                     'email_verified_at' => $legacyUser->email_verified_at,
-                     'created_at' => $legacyUser->created_at,
-                     'updated_at' => $legacyUser->updated_at,
-                 ]
-             );
-
-             $bar->advance();
-         }
-
-         $bar->finish();
-         $this->newLine();
-         $this->info('Users imported successfully!');
-     }
-
-     private function importProducts()
-     {
-         $this->info('Importing products...');
-
-         $legacyProducts = DB::connection('legacy')
-             ->table('products')
-             ->get();
-
-         $bar = $this->output->createProgressBar($legacyProducts->count());
-
-         foreach ($legacyProducts as $legacyProduct) {
-             Product::updateOrCreate(
-                 ['name' => $legacyProduct->name],
-                 [
-                     'description' => $legacyProduct->description,
-                     'price' => $legacyProduct->price,
-                     'stock' => $legacyProduct->stock ?? 0,
-                     'is_active' => $legacyProduct->is_active ?? true,
-                     'created_at' => $legacyProduct->created_at,
-                     'updated_at' => $legacyProduct->updated_at,
-                 ]
-             );
-
-             $bar->advance();
-         }
-
-         $bar->finish();
-         $this->newLine();
-         $this->info('Products imported successfully!');
-     }
- }
- ```

  ```

-
- ### Data Validation Script
-
- **Step 10.2: Create Validation Command**
- ```bash

  ```

- php artisan make:command ValidateImportedData
- ```

  ```

-
- ```php

  ```

- // app/Console/Commands/ValidateImportedData.php
- <?php
-
- namespace App\Console\Commands;
-
- use App\Models\Product;
- use App\Models\User;
- use Illuminate\Console\Command;
-
- class ValidateImportedData extends Command
- {
-     protected $signature = 'validate:imported-data';
-     protected $description = 'Validate imported data integrity';
-
-     public function handle()
-     {
-         $this->info('Validating imported data...');
-
-         $this->validateUsers();
-         $this->validateProducts();
-
-         $this->info('Data validation completed!');
-     }
-
-     private function validateUsers()
-     {
-         $this->info('Validating users...');
-
-         $invalidUsers = User::whereNull('email')
-             ->orWhere('email', '')
-             ->orWhereNull('name')
-             ->orWhere('name', '')
-             ->count();
-
-         if ($invalidUsers > 0) {
-             $this->error("Found {$invalidUsers} invalid users");
-         } else {
-             $this->info('All users are valid');
-         }
-
-         $duplicateEmails = User::select('email')
-             ->groupBy('email')
-             ->havingRaw('COUNT(*) > 1')
-             ->count();
-
-         if ($duplicateEmails > 0) {
-             $this->error("Found {$duplicateEmails} duplicate email addresses");
-         } else {
-             $this->info('No duplicate emails found');
-         }
-     }
-
-     private function validateProducts()
-     {
-         $this->info('Validating products...');
-
-         $invalidProducts = Product::whereNull('name')
-             ->orWhere('name', '')
-             ->orWhere('price', '<', 0)
-             ->orWhere('stock', '<', 0)
-             ->count();
-
-         if ($invalidProducts > 0) {
-             $this->error("Found {$invalidProducts} invalid products");
-         } else {
-             $this->info('All products are valid');
-         }
-     }
- }
- ```

  ```

-
- ## Final Migration Checklist
-
- ### Pre-Launch Verification
-
- #### Functionality Testing
- - [ ] User registration and login
- - [ ] Password reset functionality
- - [ ] Admin panel access and navigation
- - [ ] CRUD operations for all resources
- - [ ] File uploads and downloads
- - [ ] Email notifications
- - [ ] Search functionality
- - [ ] Pagination and filtering
- - [ ] Export/import features
- - [ ] API endpoints (if applicable)
-
- #### Data Integrity
- - [ ] All legacy data imported correctly
- - [ ] Relationships maintained
- - [ ] No data corruption
- - [ ] Proper data validation
- - [ ] Backup verification
-
- #### Security Verification
- - [ ] Authentication working properly
- - [ ] Authorization rules enforced
- - [ ] CSRF protection active
- - [ ] SQL injection prevention
- - [ ] XSS protection
- - [ ] File upload security
- - [ ] Rate limiting configured
-
- #### Performance Verification
- - [ ] Page load times acceptable
- - [ ] Database queries optimized
- - [ ] Caching working properly
- - [ ] Memory usage within limits
- - [ ] No N+1 query problems
-
- ### Go-Live Checklist
-
- #### Final Steps
- - [ ] DNS updated to point to new server
- - [ ] SSL certificate installed and configured
- - [ ] Monitoring tools configured
- - [ ] Backup systems active
- - [ ] Error tracking enabled
- - [ ] Performance monitoring active
- - [ ] User documentation distributed
- - [ ] Support team trained
- - [ ] Rollback plan prepared
- - [ ] Success metrics defined
-
- ## Conclusion
-
- This comprehensive migration plan provides a structured approach to migrating your existing system to Laravel with Filament. The estimated timeline assumes a dedicated developer working full-time, but can be adjusted based on:
-
- - **System Complexity**: More complex systems may require additional time
- - **Team Size**: Multiple developers can work in parallel on different phases
- - **Testing Requirements**: Thorough testing may extend the timeline
- - **Custom Features**: Unique requirements may need additional development time
-
- ### Success Factors
-
- 1. **Thorough Planning**: Complete analysis of existing system before starting
- 2. **Incremental Development**: Build and test in small, manageable chunks
- 3. **Regular Testing**: Test each component as it's developed
- 4. **Documentation**: Keep detailed records of changes and decisions
- 5. **Backup Strategy**: Always have a rollback plan
- 6. **User Training**: Ensure users are prepared for the new system
- 7. **Performance Monitoring**: Track system performance from day one
- 8. **Security Focus**: Prioritize security throughout the migration
-
- ### Risk Mitigation
-
- #### High-Risk Areas
- - **Data Migration**: Risk of data loss or corruption
- - _Mitigation_: Multiple backups, validation scripts, staged migration
- - **Authentication**: Risk of users being locked out
- - _Mitigation_: Thorough testing, fallback authentication methods
- - **Performance**: Risk of slow system performance
- - _Mitigation_: Load testing, query optimization, caching strategy
- - **Security**: Risk of security vulnerabilities
- - _Mitigation_: Security audits, penetration testing, regular updates
-
- #### Medium-Risk Areas
- - **User Adoption**: Risk of user resistance to new system
- - _Mitigation_: User training, gradual rollout, feedback collection
- - **Integration**: Risk of third-party integration failures
- - _Mitigation_: Early testing, fallback options, vendor communication
- - **Customization**: Risk of over-customization leading to maintenance issues
- - _Mitigation_: Follow Laravel/Filament best practices, code reviews
-
- ### Post-Migration Optimization
-
- #### Week 5-6: Performance Tuning
-
- **Database Optimization**
- ```sql

  ```

- -- Add indexes for frequently queried columns
- CREATE INDEX idx_products_active_created ON products(is_active, created_at);
- CREATE INDEX idx_orders_user_status ON orders(user_id, status);
- CREATE INDEX idx_users_email_verified ON users(email_verified_at);
-
- -- Analyze query performance
- EXPLAIN SELECT \* FROM products WHERE is_active = 1 ORDER BY created_at DESC;
- ```

  ```

-
- **Caching Strategy**
- ```php

  ```

- // config/cache.php - Production configuration
- 'default' => env('CACHE_DRIVER', 'redis'),
-
- 'stores' => [
-     'redis' => [
-         'driver' => 'redis',
-         'connection' => 'cache',
-         'lock_connection' => 'default',
-     ],
- ],
- ```

  ```

-
- **Queue Configuration**
- ```php

  ```

- // config/queue.php - Production configuration
- 'default' => env('QUEUE_CONNECTION', 'redis'),
-
- 'connections' => [
-     'redis' => [
-         'driver' => 'redis',
-         'connection' => 'default',
-         'queue' => env('REDIS_QUEUE', 'default'),
-         'retry_after' => 90,
-         'block_for' => null,
-     ],
- ],
- ```

  ```

-
- #### Monitoring Setup
-
- **Laravel Telescope (Development)**
- ```bash

  ```

- composer require laravel/telescope --dev
- php artisan telescope:install
- php artisan migrate
- ```

  ```

-
- **Production Monitoring**
- ```bash

  ```

- # Install monitoring packages
- composer require bugsnag/bugsnag-laravel
- composer require spatie/laravel-health
-
- # Configure health checks
- php artisan vendor:publish --tag=health-config
- ```

  ```

-
- **Health Check Configuration**
- ```php

  ```

- // config/health.php
- <?php
-
- use Spatie\Health\Checks\Checks\DatabaseCheck;
- use Spatie\Health\Checks\Checks\CacheCheck;
- use Spatie\Health\Checks\Checks\QueueCheck;
- use Spatie\Health\Checks\Checks\StorageCheck;
-
- return [
-     'checks' => [
-         DatabaseCheck::new(),
-         CacheCheck::new(),
-         QueueCheck::new()->onQueue('default'),
-         StorageCheck::new()->disk('local'),
-     ],
- ];
- ```

  ```

-
- ### Maintenance & Updates
-
- #### Regular Maintenance Tasks
-
- **Weekly Tasks**
- - [ ] Review error logs
- - [ ] Check system performance metrics
- - [ ] Verify backup integrity
- - [ ] Update dependencies (security patches)
- - [ ] Review user feedback
-
- **Monthly Tasks**
- - [ ] Database optimization
- - [ ] Security audit
- - [ ] Performance review
- - [ ] User access review
- - [ ] Documentation updates
-
- **Quarterly Tasks**
- - [ ] Major dependency updates
- - [ ] Feature usage analysis
- - [ ] Capacity planning review
- - [ ] Disaster recovery testing
- - [ ] Security penetration testing
-
- #### Update Procedures
-
- **Laravel Updates**
- ```bash

  ```

- # Check current version
- php artisan --version
-
- # Update to latest patch version
- composer update laravel/framework
-
- # Major version upgrade (follow Laravel upgrade guide)
- composer require laravel/framework:^11.0
- php artisan migrate
- ```

  ```

-
- **Filament Updates**
- ```bash

  ```

- # Update Filament packages
- composer update filament/filament
- composer update bezhansalleh/filament-shield
-
- # Clear caches after update
- php artisan filament:clear-cached-components
- php artisan optimize:clear
- ```

  ```

-
- ### Scaling Considerations
-
- #### Horizontal Scaling
-
- **Load Balancer Configuration**
- ```nginx

  ```

- upstream laravel_backend {
-     server 192.168.1.10:80;
-     server 192.168.1.11:80;
-     server 192.168.1.12:80;
- }
-
- server {
-     listen 80;
-     server_name your-domain.com;
-
-     location / {
-         proxy_pass http://laravel_backend;
-         proxy_set_header Host $host;
-         proxy_set_header X-Real-IP $remote_addr;
-         proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
-         proxy_set_header X-Forwarded-Proto $scheme;
-     }
- }
- ```

  ```

-
- **Session Configuration for Multiple Servers**
- ```php

  ```

- // config/session.php
- 'driver' => env('SESSION_DRIVER', 'redis'),
- 'connection' => 'session',
- ```

  ```

-
- #### Database Scaling
-
- **Read Replicas**
- ```php

  ```

- // config/database.php
- 'mysql' => [
-     'read' => [
-         'host' => [
-             '192.168.1.1',
-             '192.168.1.2',
-         ],
-     ],
-     'write' => [
-         'host' => [
-             '192.168.1.3',
-         ],
-     ],
-     'sticky' => true,
-     // ... other configuration
- ],
- ```

  ```

-
- ### Documentation Templates
-
- #### User Manual Template
- ```markdown

  ```

- # User Manual - [System Name]
-
- ## Getting Started
- ### Logging In
- ### Dashboard Overview
- ### Navigation
-
- ## Core Features
- ### [Feature 1]
- ### [Feature 2]
- ### [Feature 3]
-
- ## Troubleshooting
- ### Common Issues
- ### Contact Support
-
- ## FAQ
- ```

  ```

-
- #### Admin Guide Template
- ```markdown

  ```

- # Administrator Guide - [System Name]
-
- ## User Management
- ### Creating Users
- ### Managing Roles
- ### Permissions
-
- ## System Configuration
- ### Settings
- ### Maintenance Mode
- ### Backups
-
- ## Monitoring
- ### Performance Metrics
- ### Error Logs
- ### Health Checks
-
- ## Troubleshooting
- ### Common Admin Tasks
- ### Emergency Procedures
- ```

  ```

-
- #### API Documentation Template
- ```markdown

  ```

- # API Documentation - [System Name]
-
- ## Authentication
- ### Getting API Token
- ### Using Bearer Token
-
- ## Endpoints
-
- ### Products
- #### GET /api/v1/products
- #### POST /api/v1/products
- #### GET /api/v1/products/{id}
- #### PUT /api/v1/products/{id}
- #### DELETE /api/v1/products/{id}
-
- ## Error Handling
- ### Error Codes
- ### Error Responses
-
- ## Rate Limiting
- ## Pagination
- ## Filtering
- ```

  ```

-
- ### Success Metrics & KPIs
-
- #### Technical Metrics
- - **Performance**
- - Page load time < 2 seconds
- - API response time < 500ms
- - Database query time < 100ms
- - 99.9% uptime
-
- - **Security**
- - Zero security vulnerabilities
- - All data encrypted in transit and at rest
- - Regular security audits passed
- - Compliance requirements met
-
- - **Reliability**
- - Error rate < 0.1%
- - Successful backup completion rate 100%
- - Recovery time objective < 4 hours
- - Recovery point objective < 1 hour
-
- #### Business Metrics
- - **User Adoption**
- - User login frequency
- - Feature usage statistics
- - User satisfaction scores
- - Support ticket reduction
-
- - **Operational Efficiency**
- - Reduced manual processes
- - Improved data accuracy
- - Faster report generation
- - Reduced maintenance overhead
-
- ### Final Notes
-
- This migration plan is comprehensive but should be adapted to your specific needs. Key considerations:
-
- 1. **Start Small**: Begin with core functionality and gradually add features
- 2. **Test Thoroughly**: Never skip testing phases
- 3. **Document Everything**: Good documentation saves time later
- 4. **Plan for Rollback**: Always have a way to revert changes
- 5. **Monitor Continuously**: Keep track of system health and performance
- 6. **Stay Updated**: Keep Laravel, Filament, and dependencies current
- 7. **Security First**: Never compromise on security measures
- 8. **User Experience**: Prioritize user-friendly interfaces and workflows
-
- Remember that migration is not just about moving code—it's about improving your system's maintainability, security, and user experience. Take time to do it right, and you'll have a solid foundation for future growth.
-
- ### Support Resources
-
- - **Laravel Documentation**: https://laravel.com/docs
- - **Filament Documentation**: https://filamentphp.com/docs
- - **Filament Shield Documentation**: https://github.com/bezhanSalleh/filament-shield
- - **Laravel Community**: https://laracasts.com, https://laravel.io
- - **Filament Community**: https://discord.gg/filament
-
- Good luck with your migration! 🚀
