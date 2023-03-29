if (Session::get('role') !== 'owner') {
  notify()->error('Akses dilarang.');
  return back();
}
if (Session::get('role') === 'supervisor' || Session::get('role') === 'manager') {
    notify()->error('Akses dilarang.');
    return back();
}
if (Session::get('role') !== 'owner' || Session::get('role') !== 'admin') {
    notify()->error('Akses dilarang.');
    return back();
}
if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
}
if (Session::get('role') !== 'owner' || Session::get('role') !== 'manager') {
  notify()->error('Akses dilarang.');
  return back();
}
$cek = MandorProyek::where('id', $id)->first();
        if ($cek->approved !== 1) {
            notify()->error('Akses dilarang.');
            return back();
        }

$id = $request->input('id');
$cek = Bukukas::where('id',$id)->first();
if (Session::get('tahun') !== Carbon::parse(now())->year || $cek->tahun !== Carbon::parse(now())->year) {
    notify()->error('Akses dilarang.');
    return back();
}

if (Session::get('tahun') !== Carbon::parse(now())->year) {
            notify()->error('Akses dilarang.');
            return back();
}

Session::get('tahun') === $carbon->parse(now())->year