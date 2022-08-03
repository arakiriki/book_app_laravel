<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __('本の管理') }}
      </h2>
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 bg-white border-b border-gray-200">
                
                <table class="table-auto w-full text-left whitespace-no-wrap">
                  <thead>
                    <tr>
                      <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">ID</th>
                      <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">名前</th>
                      <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">ステータス</th>
                      <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">著者</th>
                      <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">出版社</th>
                      <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">読み終わった日</th>
                      <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">メモ</th>
                      <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($books as $book)
                    <tr>
                      <td class="border-t-2 border-gray-200 px-4 py-3">{{ $book->id }}</td>
                      <td class="border-t-2 border-gray-200 px-4 py-3">{{ $book->name }}</td>
                      <td class="border-t-2 border-gray-200 px-4 py-3">{{ $book->status }}</td>
                      <td class="border-t-2 border-gray-200 px-4 py-3">{{ $book->author }}</td>
                      <td class="border-t-2 border-gray-200 px-4 py-3">{{ $book->publication }}</td>
                      <td class="border-t-2 border-gray-200 px-4 py-3">{{ Carbon\Carbon::parse($book->read_at)->format('Y年n月j日') }}</td>
                      <td class="border-t-2 border-gray-200 px-4 py-3">{{ Str::limit($book->note, 40, $end="...") }}</td>
                      <td class="border-t-2 border-gray-200 px-4 py-3"> <button onclick="location.href='/book/detail/{{$book->id }}'" class="text-sm shadow bg-gray-500 hover:bg-gray-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded">詳細</button>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>

                {{$books->links()}}

              </div>
            </div>
          </section>

                
                
              </div>
          </div>
      </div>
  </div>
</x-app-layout>



